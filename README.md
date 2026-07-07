# 🎁 Sandee Shop — ระบบซื้อขายกล่องสุ่ม (Mystery Box)

เว็บแอปพลิเคชันซื้อขายกล่องสุ่ม (Mystery Box / Gacha) พัฒนาด้วย Laravel รองรับตั้งแต่การจัดการกล่องสุ่มฝั่งแอดมิน ไปจนถึงระบบเปิดกล่อง กระเป๋าเงิน และคลังไอเทมฝั่งผู้ใช้

---

## 📌 ภาพรวมโปรเจกต์

| หัวข้อ | รายละเอียด |
|---|---|
| ชื่อโปรเจกต์ | Sandee Shop |
| ประเภท | เว็บอีคอมเมิร์ซขายกล่องสุ่ม (คล้าย gacha / loot box) |
| Framework | Laravel 13 |
| PHP | 8.5.8 |
| ฐานข้อมูล | SQLite (`database/database.sqlite`) |
| Frontend | Blade Templates + Tailwind CSS + Alpine.js (มากับ Laravel Breeze) |
| Authentication | Laravel Breeze (Blade + Alpine stack) |
| OS ที่พัฒนา | Windows |

---

## 🛠️ เทคโนโลยีที่ใช้ (Tech Stack)

- **Backend:** Laravel 13, PHP 8.5.8
- **Database:** SQLite
- **Frontend:** Blade, Tailwind CSS, Alpine.js, Vite
- **Authentication:** Laravel Breeze
- **Chart:** Chart.js (ผ่าน CDN) — ใช้ในแดชบอร์ดแอดมิน
- **Font:**
  - `Chakra Petch` — ฟอนต์หัวข้อ/ดิสเพลย์ (class: `font-display`)
  - `IBM Plex Sans Thai` — ฟอนต์เนื้อหาหลัก (default ของ `body`)
  - `JetBrains Mono` — ฟอนต์ตัวเลข/ราคา/เปอร์เซ็นต์ (class: `font-mono-data`)

---

## 📂 โครงสร้างฐานข้อมูล (Database Schema)

### ตารางทั้งหมด

| ตาราง | คำอธิบาย | ฟิลด์สำคัญ |
|---|---|---|
| `users` | ผู้ใช้ (ต่อยอดจาก Laravel default) | `balance` (ยอดเงิน), `role` (`user`/`admin`) |
| `categories` | หมวดหมู่กล่องสุ่ม | `name`, `slug`, `image` |
| `boxes` | กล่องสุ่ม | `category_id`, `name`, `slug`, `price`, `stock`, `status` (`active`/`inactive`) |
| `box_items` | ไอเทมในกล่องแต่ละใบ | `box_id`, `rarity` (`common`/`rare`/`epic`/`legendary`), `market_value`, `probability` (%), `stock` |
| `orders` | คำสั่งซื้อ (เติมเงิน/ซื้อกล่อง/ขายคืน) | `user_id`, `type`, `amount`, `status` |
| `box_openings` | ประวัติการเปิดกล่องแต่ละครั้ง (log) | `user_id`, `box_id`, `box_item_id` |
| `user_inventories` | คลังไอเทมของผู้ใช้ | `user_id`, `box_item_id`, `status` (`owned`/`sold`/`shipped`) |
| `transactions` | ประวัติธุรกรรมทางการเงินทั้งหมด | `type` (`deposit`/`purchase`/`refund`/`withdraw`), `amount`, `balance_before`, `balance_after` |

### ความสัมพันธ์ (Relationships)

```
users 1---* orders
users 1---* box_openings
users 1---* user_inventories
users 1---* transactions

categories 1---* boxes
boxes 1---* box_items
boxes 1---* box_openings

box_items 1---* box_openings
box_items 1---* user_inventories
```

⚠️ **กฎสำคัญ:** ผลรวมของ `probability` ของ `box_items` ทุกชิ้นในกล่องเดียวกัน **ต้องไม่เกิน 100%** — มี validation บังคับไว้ที่ `BoxItemController` แล้ว ถ้ารวมเกิน 100% ระบบสุ่มจะพัง

---

## 🗂️ Models (`app/Models/`)

| Model | ความสัมพันธ์หลัก |
|---|---|
| `User` | `orders()`, `boxOpenings()`, `inventories()`, `transactions()`, มีเมธอด `isAdmin()` |
| `Category` | `boxes()` |
| `Box` | `category()`, `items()`, `openings()` |
| `BoxItem` | `box()`, `inventories()` |
| `Order` | `user()` |
| `BoxOpening` | `user()`, `box()`, `boxItem()` |
| `UserInventory` | `user()`, `boxItem()` |
| `Transaction` | `user()` |

---

## 🧭 Routes ทั้งหมด (`routes/web.php`)

### Public (ไม่ต้องล็อกอิน)
```
GET  /                      welcome page
GET  /shop                  shop.index      — รายการกล่องสุ่มทั้งหมด
GET  /shop/{box:slug}       shop.show       — รายละเอียดกล่อง + เปิดกล่อง
```

### ต้องล็อกอิน (`middleware: auth`)
```
GET   /dashboard                    dashboard              — Dashboard ผู้ใช้
GET   /profile                      profile.edit
PATCH /profile                      profile.update
DELETE /profile                     profile.destroy

GET   /wallet                       wallet.index           — กระเป๋าเงิน + ประวัติธุรกรรม
GET   /wallet/topup                 wallet.topup.form
POST  /wallet/topup                 wallet.topup           — เติมเงิน (จำลอง ยังไม่เชื่อม payment จริง)

POST  /shop/{box}/open              box.open               — เปิดกล่องสุ่ม (AJAX, คืนค่า JSON)

GET   /inventory                    inventory.index        — คลังไอเทมของผู้ใช้
POST  /inventory/{inventory}/sell   inventory.sell         — ขายไอเทมคืน
```

### เฉพาะแอดมิน (`middleware: auth, admin` / prefix: `admin.`)
```
GET  /admin/dashboard                admin.dashboard         — แดชบอร์ดแอดมิน (ยอดขาย, กราฟ, สถิติ)

/admin/categories        (resource)  admin.categories.*      — CRUD หมวดหมู่
/admin/boxes             (resource)  admin.boxes.*           — CRUD กล่องสุ่ม
/admin/boxes/{box}/items (nested)    admin.boxes.items.*     — จัดการไอเทมในกล่อง (shallow route)
/admin/items/{item}                  admin.items.*           — edit/update/destroy ไอเทม (shallow)
```

---

## 🎮 Controllers และหน้าที่

| Controller | หน้าที่ |
|---|---|
| `DashboardController` | Dashboard ผู้ใช้ทั่วไป (สรุปยอดเงิน, ไอเทม, ประวัติ) |
| `ShopController` | หน้าร้านค้า + รายละเอียดกล่อง (`index`, `show`) |
| `WalletController` | กระเป๋าเงิน + เติมเงิน (`index`, `topupForm`, `topup`) |
| `BoxOpeningController` | เรียกใช้ `BoxOpeningService` เพื่อเปิดกล่อง คืนผล JSON |
| `InventoryController` | คลังไอเทม + ขายคืน (`index`, `sell`) |
| `Admin\CategoryController` | CRUD หมวดหมู่ |
| `Admin\BoxController` | CRUD กล่องสุ่ม |
| `Admin\BoxItemController` | CRUD ไอเทมในกล่อง (มี validate probability รวมไม่เกิน 100%) |
| `Admin\DashboardController` | สรุปยอดขาย, กราฟ 7 วัน, กล่องขายดี, ธุรกรรมล่าสุด |

---

## 🎲 Business Logic สำคัญ: `BoxOpeningService`

ไฟล์: `app/Services/BoxOpeningService.php`

เป็นหัวใจของระบบทั้งหมด รวม 4 ขั้นตอนไว้ใน **DB Transaction เดียว**:

1. ล็อกแถวข้อมูล (`lockForUpdate()`) ของทั้ง `Box` และ `User` ป้องกัน **race condition** (กันคนเปิดกล่องพร้อมกันหลายครั้งจนสต็อก/เงินคำนวณผิด)
2. เช็คเงื่อนไข: กล่อง active ไหม, สต็อกพอไหม, เงินพอไหม, มีไอเทมเหลือให้สุ่มไหม
3. สุ่มไอเทมด้วยหลัก **cumulative probability** (สุ่มเลข 0–100 แล้วไล่บวกสะสม probability ทีละไอเทมจนกว่าจะเกิน)
4. หักเงิน + หักสต็อกกล่อง/ไอเทม + บันทึก `BoxOpening` + เพิ่ม `UserInventory` + บันทึก `Transaction` พร้อมกันทั้งหมด

⚠️ **หลักการความปลอดภัย:** ผลการสุ่มทั้งหมดเกิดขึ้นที่ **server เท่านั้น** ฝั่ง frontend (แอนิเมชันสปิน) เป็นแค่การนำเสนอผลที่ได้มาแล้ว ไม่ได้สุ่มซ้ำที่ browser — ป้องกันผู้ใช้แก้ไขผลลัพธ์ผ่าน DevTools

---

## 🎨 Design System

ธีม: **"Collector's Vault"** — ห้องนิรภัยสะสมของหายาก ผสมกลิ่นอาย gacha/trading card

### สี (CSS Variables ใน `resources/css/app.css`)

| ตัวแปร | ค่าสี | ใช้ที่ไหน |
|---|---|---|
| `--void` | `#14112B` | แถบเมนูบนสุด, การ์ดยอดเงิน gradient |
| `--vault` | `#1E1A3D` | พื้นหลัง dropdown, การ์ดโหมดมืด |
| `--vault-light` | `#322C5C` | เส้นขอบในโหมดมืด |
| `--gold` | `#E7B24C` | ราคา, ปุ่มสำคัญ, ของ Legendary (ใช้อย่างประหยัด) |
| `--violet` | `#7C5CFC` | ลิงก์, focus, ของ Epic, เมนูแอดมิน |
| `--ink` | `#F3F1FA` | ตัวอักษรบนพื้นเข้ม |
| `--ink-dim` | `#9C97BE` | ตัวอักษรรองบนพื้นเข้ม |
| พื้นหลังเว็บ | `#F5F4FB` | ตั้งไว้ที่ `body` |

### ฟอนต์ (Utility Classes)

- `.font-display` → Chakra Petch (หัวข้อ, ชื่อกล่อง/ไอเทม, ราคาตัวใหญ่)
- `.font-mono-data` → JetBrains Mono (ตัวเลขเงิน, เปอร์เซ็นต์, ข้อมูลในตาราง)
- ค่า default ของ `body` → IBM Plex Sans Thai (เนื้อหาทั่วไป)

### ลายเซ็นของธีม (Signature Element)

`.card-foil` — คลาส CSS ที่ใส่เส้นขอบบนไล่สีทอง→ม่วง (3px) ให้กับการ์ดทุกใบในเว็บ (กล่องสุ่ม, ไอเทม, สถิติ) เพื่อให้มีเอกลักษณ์เดียวกันทั้งเว็บ

### แอนิเมชันเปิดกล่อง

หน้า `shop/show.blade.php` มีระบบแอนิเมชัน 3 stage:
1. **Shake** — กล่อง 🎁 สั่นด้วย CSS keyframe 1.5 วินาที
2. **Spin** — แถบไอเทม 25 ชิ้นสุ่มเลื่อนผ่านจอ (แทรกไอเทมจริงที่ตำแหน่งที่ 20) หยุดตรงเส้นชี้สีทอง
3. **Result** — เผยไอเทมพร้อม glow effect ตามระดับ rarity (legendary เรืองแสงแรงสุด)

---

## ✅ ฟีเจอร์ที่ทำเสร็จแล้ว

- [x] ระบบสมัครสมาชิก/ล็อกอิน (Laravel Breeze)
- [x] ระบบสิทธิ์ผู้ใช้ (`user` / `admin`) ผ่าน `AdminMiddleware`
- [x] แอดมิน: CRUD หมวดหมู่กล่องสุ่ม
- [x] แอดมิน: CRUD กล่องสุ่ม (พร้อมอัปโหลดรูป)
- [x] แอดมิน: CRUD ไอเทมในกล่อง (validate probability รวมไม่เกิน 100%)
- [x] แอดมิน: แดชบอร์ดสรุปยอดขาย + กราฟ 7 วัน + กล่องขายดี
- [x] หน้าร้านค้า (filter ตามหมวดหมู่) + หน้ารายละเอียดกล่อง
- [x] ระบบกระเป๋าเงิน + เติมเงิน (จำลอง ยังไม่เชื่อม payment gateway จริง)
- [x] ระบบเปิดกล่องสุ่มจริง (สุ่มตาม probability, หักเงิน/สต็อกแบบ transaction ปลอดภัย)
- [x] แอนิเมชันเปิดกล่อง 3 stage (shake → spin → result พร้อม glow)
- [x] ระบบคลังไอเทม + ขายคืน (คืนเงินตาม market_value)
- [x] Dashboard ผู้ใช้ (สรุปยอดเงิน, สถิติ, ไอเทมล่าสุด, ประวัติเปิดกล่อง)
- [x] รีดีไซน์ทั้งเว็บด้วยธีม "Collector's Vault"
- [x] เปลี่ยนชื่อแบรนด์เป็น "Sandee Shop"

---

## 🚧 แผนที่ยังไม่ได้ทำ (TODO ต่อไป)

- [ ] **Pity System** — การันตีของหายาก เปิดครบ N ครั้งการันตีได้ของดีอย่างน้อย 1 ชิ้น
- [ ] **Leaderboard** — จัดอันดับผู้ใช้ที่ได้ของหายากที่สุด/ใช้เงินเยอะที่สุด
- [ ] **Live Feed** — แสดงว่าใครเพิ่งเปิดกล่องได้อะไรแบบเรียลไทม์ในหน้าร้าน
- [ ] **Payment Gateway จริง** — เชื่อมต่อ Omise/Stripe/พร้อมเพย์ แทนการเติมเงินจำลอง
- [ ] **Seeder** — สร้างข้อมูลตัวอย่างจำนวนมากไว้เดโม่/ทดสอบ
- [ ] **Deploy** — เตรียมเซิร์ฟเวอร์จริง (เปลี่ยนจาก SQLite เป็น MySQL/PostgreSQL แนะนำสำหรับ production, ตั้งค่า `.env` production, queue, cache)
- [ ] ระบบเลเวล/แต้มสะสม, ระบบแจ้งเตือน (ตามที่เคยเสนอไว้)

---

## ⚙️ วิธีติดตั้งโปรเจกต์ใหม่ (Setup)

```bash
# 1. ติดตั้ง dependencies
composer install
npm install

# 2. ตั้งค่า environment
copy .env.example .env
php artisan key:generate

# 3. ตั้งค่าฐานข้อมูล (.env)
# DB_CONNECTION=sqlite
# สร้างไฟล์ database/database.sqlite ถ้ายังไม่มี

# 4. รัน migration
php artisan migrate

# 5. สร้าง storage link (สำหรับรูปภาพที่อัปโหลด)
php artisan storage:link

# 6. Build frontend
npm run build
# หรือระหว่างพัฒนาใช้ (auto-compile ทุกครั้งที่แก้ไฟล์):
npm run dev

# 7. รันเซิร์ฟเวอร์
php artisan serve
```

### ตั้งให้ user เป็นแอดมิน (ผ่าน Tinker)

```bash
php artisan tinker
```
```php
$user = App\Models\User::where('email', 'your@email.com')->first();
$user->role = 'admin';
$user->save();
```

---

## 📝 หมายเหตุสำคัญสำหรับการพัฒนาต่อ

1. **เปิด `npm run dev` ค้างไว้ระหว่างพัฒนาเสมอ** (คู่กับ `php artisan serve`) — ไม่งั้น class Tailwind ใหม่ที่เพิ่มเข้ามาจะไม่ถูก compile ทำให้ CSS ไม่แสดงผล (ปัญหานี้เจอบ่อยระหว่างพัฒนาโปรเจกต์นี้)
2. **ทุกจุดที่เกี่ยวกับเงิน/สต็อกต้องใช้ `DB::transaction()` + `lockForUpdate()`** ตามแบบใน `BoxOpeningService` เพื่อป้องกันข้อมูลไม่ตรงกันเวลามีคนใช้งานพร้อมกัน
3. **Probability ของไอเทมในกล่องเดียวกันต้องรวมกันไม่เกิน 100%** เสมอ — มี validation ป้องกันไว้แล้วที่ `BoxItemController` ห้ามลบ validation นี้ออก
4. โครงสร้างสี/ฟอนต์ทั้งหมดอยู่ใน `resources/css/app.css` — ถ้าจะเพิ่มหน้าใหม่ให้ใช้ variable และ class เดิม (`--gold`, `--violet`, `.card-foil`, `.font-display`, `.font-mono-data`) เพื่อความสม่ำเสมอของธีม
5. Routes ทั้งหมดอยู่ในไฟล์เดียว `routes/web.php` — ถ้าคลาสชื่อซ้ำกันระหว่าง namespace (เช่น `DashboardController` ของ user กับของ admin) ให้ใช้ `use ... as ...` เพื่อเลี่ยง error ชื่อชนกัน