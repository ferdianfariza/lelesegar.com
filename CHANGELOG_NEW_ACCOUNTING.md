# Changelog - New Accounting System Requirements

## Overview
This document describes the major changes made to the lelesegar.com accounting system based on new business requirements.

## Date: November 5, 2025

---

## 1. Setup Awal (Initial Setup) Group

### Changes:

#### 1.1 Modal & Prive - HIDDEN
- **Status**: Hidden from navigation (resource still exists but not accessible via menu)
- **Reason**: Modal is now tracked via Income Transactions with type "modal_awal"
- **File**: `app/Filament/Resources/EquityTransactionResource.php`

#### 1.2 Aset/Peralatan → Aset
- **Old Name**: "Aset/Peralatan"
- **New Name**: "Aset"
- **Default Type Changed**: From "peralatan" to "bangunan"
- **Asset Types Available**:
  - Bangunan (Building) - default
  - Peralatan (Equipment)
  - Kendaraan (Vehicle)
  - Lainnya (Other)
- **Purpose**: Focus on asset depreciation tracking
- **File**: `app/Filament/Resources/AssetResource.php`

#### 1.3 Produk (Products)
- **Status**: Remains unchanged
- **Note**: Products are NOT included in inventory as they are finished goods, not raw materials

---

## 2. Transaksi (Transaction) Group

### Changes:

#### 2.1 Pemasukan (Income) - NEW TYPE ADDED
- **New Income Type**: "Modal Awal" (Initial Capital)
- **Income Types Now**:
  1. Penjualan (Sales)
  2. **Modal Awal (Initial Capital)** ← NEW
  3. Tambah Modal (Additional Capital)
  4. Lainnya (Other)
- **Purpose**: Track initial capital investment separately
- **File**: `app/Filament/Resources/IncomeTransactionResource.php`

#### 2.2 Pengeluaran (Expenses) - CATEGORIES REPLACED
- **Old Categories**: Gaji, Bahan Baku, Listrik, Air, Telepon, Transportasi, Pemeliharaan, Sewa, Lain-lain
- **New Categories**:
  1. Peralatan (Equipment)
  2. Bangunan (Building)
  3. Kendaraan (Vehicle)
  4. Beban Produksi (Production Expenses)
  5. Prive (Owner's Withdrawal)
  6. Lainnya (Other)
- **Purpose**: Better categorization for financial reporting
- **File**: `database/seeders/ExpenseCategorySeeder.php`

#### 2.3 Bahan Baku (Raw Materials) - NEW SYSTEM
Replaces "Penambahan Bahan Baku" (Stock Addition)

**New Models**:
- `RawMaterial` - Master data for raw materials
- `RawMaterialUsage` - Track usage of raw materials

**RawMaterial Features**:
- Code and name
- Unit of measurement (pcs, kg, liter, etc.)
- Price per unit
- Beginning stock
- Current stock (auto-updated via usage)
- Minimum stock (for alerts)
- Status (active/inactive)

**Default Raw Materials Seeded**:
1. Cup Plastik (Plastic Cups) - 1000 pcs @ Rp 500
2. Teh Celup (Tea Bags) - 50 pack @ Rp 15.000
3. Kopi Bubuk (Coffee Powder) - 20 kg @ Rp 80.000
4. Gula Pasir (Sugar) - 50 kg @ Rp 15.000
5. Susu Kental Manis (Sweetened Condensed Milk) - 48 cans @ Rp 12.000

**RawMaterialUsage Features**:
- Select raw material
- Usage date
- Quantity used
- Price per unit (auto-filled from raw material)
- Total cost (auto-calculated)
- Notes
- **Automatic stock reduction via Observer**
- **Stock validation** (prevents negative inventory)

**Stock Flow**:
```
Beginning Stock → (+) Purchases → (-) Usage = Ending Stock
```

**Observer Features**:
- Auto-calculates total_cost on create/update
- Reduces current_stock when usage is recorded
- Validates sufficient stock before reducing
- Throws exception if stock insufficient
- Handles quantity updates properly

**Files Created**:
- `app/Models/RawMaterial.php`
- `app/Models/RawMaterialUsage.php`
- `app/Filament/Resources/RawMaterialResource.php`
- `app/Filament/Resources/RawMaterialUsageResource.php`
- `app/Observers/RawMaterialUsageObserver.php`
- `database/migrations/2025_11_05_101810_create_raw_materials_table.php`
- `database/migrations/2025_11_05_101820_create_raw_material_usages_table.php`
- `database/seeders/RawMaterialSeeder.php`

#### 2.4 Hidden Resources
The following are hidden from navigation:
- Penambahan Bahan Baku (StockAdditionResource) - replaced by RawMaterial system
- Persediaan (InventoryResource)
- Mutasi Persediaan (InventoryMovementResource)

---

## 3. Laporan (Reports) Group

### Changes:

#### 3.1 Laporan Laba Rugi (Income Statement)

**Revenue Section**:
- Only shows "Pendapatan Penjualan" (Sales Revenue)
- Removed "Pendapatan Lain-lain" (Other Revenue)
- Source: Income transactions with type = 'sales'

**Expenses Section**:
- **Beban Produksi (Production Expenses)**:
  - From expense transactions with category "Beban Produksi"
  - **Detailed Raw Material Usage Breakdown**:
    - Lists each raw material used
    - Shows quantity and unit
    - Shows cost per material
  - Subtotal: Total Beban Produksi
- Other expense categories
- **Total Beban** (Total Expenses)

**Net Income Calculation**:
```
Sales Revenue - Total Expenses = Net Income
```

**File**: `app/Filament/Pages/IncomeStatement.php`

#### 3.2 Laporan Perubahan Ekuitas (Equity Statement)

**Structure** (Simplified):
- Modal Awal (Initial Capital)
  - Source: Income transactions with type = 'initial_capital' before period
- (+) Tambahan Modal (Additional Capital)
  - Source: Income transactions with type = 'initial_capital' during period
- (+/-) Laba/Rugi Bersih (Net Income)
  - Source: From Income Statement calculation
- **Removed**: Prive (Owner's Withdrawal)
- = Modal Akhir (Ending Equity)

**Calculation**:
```
Ending Equity = Initial Capital + Additional Capital + Net Income
```

**File**: `app/Filament/Pages/EquityStatement.php`

#### 3.3 Laporan Posisi Keuangan (Balance Sheet/Neraca)

**Assets Section**:
- **Aset Lancar (Current Assets)**:
  - **Kas (Cash)**:
    ```
    Total Income - Total Expenses - Raw Material Purchases
    ```
    - Raw Material Purchases = Sum of (beginning_stock × price_per_unit) for all raw materials
  - **Persediaan Bahan Baku (Raw Material Inventory)**:
    ```
    Sum of (current_stock × price_per_unit) for all raw materials
    ```

- **Aset Tetap (Fixed Assets)**:
  - From expense transactions with categories:
    - Peralatan (Equipment)
    - Bangunan (Building)
    - Kendaraan (Vehicle)

**Liabilities & Equity Section**:
- **Kewajiban (Liabilities)**:
  - Utang (Debts) - unpaid debts only

- **Ekuitas (Equity)**:
  - Modal (Capital) = Ending Equity from Equity Statement
  - **Removed**: Prive, Laba Ditahan (Retained Earnings)

**Balance Check**:
```
Total Assets = Total Liabilities + Total Equity
```

**File**: `app/Filament/Pages/BalanceSheet.php`

#### 3.4 Hidden Reports
- Persediaan (Inventory)
- Mutasi Persediaan (Inventory Movement)

---

## 4. Database Changes

### New Tables:
1. `raw_materials`
   - id, name, code, description, unit
   - price_per_unit, beginning_stock, current_stock, minimum_stock
   - is_active, timestamps

2. `raw_material_usages`
   - id, raw_material_id (FK)
   - usage_date, quantity, price_per_unit, total_cost
   - notes, timestamps

### Modified Seeders:
- `ExpenseCategorySeeder` - new categories
- `RawMaterialSeeder` - default raw materials

---

## 5. Business Logic

### Raw Material Stock Flow:
1. **Purchase**: Create RawMaterial with beginning_stock
2. **Usage**: Create RawMaterialUsage
   - Automatically reduces current_stock
   - Calculates total_cost
   - Validates sufficient stock
3. **Ending Stock**: current_stock value
4. **Inventory Value**: current_stock × price_per_unit

### Financial Calculations:

**Income Statement**:
```
Sales Revenue
- Production Expenses (from expense category)
- Raw Material Usage (sum of total_cost)
- Other Expenses
= Net Income
```

**Equity Statement**:
```
Initial Capital (before period)
+ Additional Capital (during period)
+ Net Income (from Income Statement)
= Ending Equity
```

**Balance Sheet**:
```
Assets:
  Cash = Total Income - Total Expenses - Raw Material Purchases
  Raw Material Inventory = Ending Stock Value
  Fixed Assets = Equipment + Building + Vehicle expenses
  
Equity:
  Modal = Ending Equity (from Equity Statement)

Assets = Equity
```

---

## 6. Testing Results

### Sample Test Data:
- Initial Capital: Rp 10.000.000
- Sales Revenue: Rp 500.000
- Production Expense: Rp 100.000
- Raw Material Usage: 100 cups @ Rp 500 = Rp 50.000

### Calculated Results:
✅ Net Income: Rp 350.000
✅ Cash: Rp 6.224.000
✅ Raw Material Inventory: Rp 4.126.000
✅ Total Assets: Rp 10.350.000
✅ Total Equity: Rp 10.350.000
✅ Balance: Assets = Equity ✓

---

## 7. Migration Guide

### For Existing Data:

1. **Run new migrations**:
   ```bash
   php artisan migrate
   ```

2. **Seed new data**:
   ```bash
   php artisan db:seed --class=ExpenseCategorySeeder
   php artisan db:seed --class=RawMaterialSeeder
   ```

3. **Update existing data**:
   - Transfer any "modal awal" entries from EquityTransaction to IncomeTransaction
   - Reclassify expense transactions to new categories
   - Create initial raw material records

### For Fresh Installation:
```bash
php artisan migrate:fresh --seed
```

---

## 8. Files Modified/Created

### Models:
- ✨ `app/Models/RawMaterial.php` (new)
- ✨ `app/Models/RawMaterialUsage.php` (new)

### Resources:
- ✨ `app/Filament/Resources/RawMaterialResource.php` (new)
- ✨ `app/Filament/Resources/RawMaterialUsageResource.php` (new)
- 📝 `app/Filament/Resources/AssetResource.php` (modified)
- 📝 `app/Filament/Resources/EquityTransactionResource.php` (hidden)
- 📝 `app/Filament/Resources/IncomeTransactionResource.php` (modified)
- 📝 `app/Filament/Resources/InventoryResource.php` (hidden)
- 📝 `app/Filament/Resources/InventoryMovementResource.php` (hidden)
- 📝 `app/Filament/Resources/StockAdditionResource.php` (hidden)

### Observers:
- ✨ `app/Observers/RawMaterialUsageObserver.php` (new)
- 📝 `app/Providers/AppServiceProvider.php` (modified)

### Pages:
- 📝 `app/Filament/Pages/IncomeStatement.php` (modified)
- 📝 `app/Filament/Pages/EquityStatement.php` (modified)
- 📝 `app/Filament/Pages/BalanceSheet.php` (modified)

### Views:
- 📝 `resources/views/filament/pages/income-statement.blade.php` (modified)
- 📝 `resources/views/filament/pages/equity-statement.blade.php` (modified)
- 📝 `resources/views/filament/pages/balance-sheet.blade.php` (modified)

### Migrations:
- ✨ `database/migrations/2025_11_05_101810_create_raw_materials_table.php` (new)
- ✨ `database/migrations/2025_11_05_101820_create_raw_material_usages_table.php` (new)

### Seeders:
- ✨ `database/seeders/RawMaterialSeeder.php` (new)
- 📝 `database/seeders/ExpenseCategorySeeder.php` (modified)
- 📝 `database/seeders/DatabaseSeeder.php` (modified)

---

## 9. Security Considerations

✅ **Stock Validation**: Prevents negative inventory
✅ **Automatic Calculations**: Reduces human error
✅ **Observer Pattern**: Ensures data consistency
✅ **Foreign Key Constraints**: Maintains referential integrity

---

## 10. Notes for Users

1. **Raw Materials**: 
   - Add new raw materials as needed via "Bahan Baku" menu
   - Set minimum stock levels for low-stock alerts
   - Monitor current stock before recording usage

2. **Initial Capital**: 
   - Record as Income transaction with type "Modal Awal"
   - Shows in Equity Statement

3. **Expense Categories**: 
   - Use "Beban Produksi" for general production costs
   - Use specific categories (Peralatan, Bangunan, Kendaraan) for asset purchases
   - These will appear in Balance Sheet as fixed assets

4. **Reports**: 
   - Income Statement shows detailed raw material usage
   - Balance Sheet shows raw material inventory value
   - Equity Statement simplified to show only capital and net income

---

## Support

For questions or issues related to these changes, please contact the development team.
