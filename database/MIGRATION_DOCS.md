# Database Migration Documentation

## Migration History Overview

This document provides an overview of all database migrations in the project.

### Core Laravel Migrations (2014-2019)
- `2014_10_12_000000_create_users_table` - Base users table
- `2014_10_12_100000_create_password_reset_tokens_table` - Password reset functionality  
- `2019_08_19_000000_create_failed_jobs_table` - Queue failed jobs tracking
- `2019_12_14_000001_create_personal_access_tokens_table` - API token management

### Application Core Tables (January 2025)
- `2025_01_15_000001_create_tidak_mampu_table` - Surat Keterangan Tidak Mampu
- `2025_01_15_000002_create_belum_menikah_table` - Surat Keterangan Belum Menikah

### System Foundation (August 2025)
- `2025_08_05_000000_create_wilayah_table` - Regional/administrative areas
- `2025_08_06_000000_create_surat_table` - Generic letter/document table
- `2025_08_13_134327_create_domisili_table` - Domicile certificate table

### Surat (Letter) Types - Created August 21-28, 2025
- `2025_08_21_000000_create_surat_ktp_table` - ID Card certificate
- `2025_08_25_231301_create_surat_kematian_table` - Death certificate  
- `2025_08_25_231326_create_surat_kelahiran_table` - Birth certificate
- `2025_08_25_231343_create_surat_skck_table` - Police background check letter
- `2025_08_25_231355_create_surat_kk_table` - Family card certificate
- `2025_08_28_125039_create_surat_usaha_table` - Business certificate
- `2025_08_28_125126_create_surat_kehilangan_table` - Loss certificate

### System Features
- `2025_08_22_143121_create_settings_table` - Application settings
- `2025_08_23_150709_add_role_to_users_table` - User role management
- `2025_08_28_130615_create_notifications_table` - Notification system

### Enhancements and Fixes (August 28-31, 2025)

#### Verification System
- `2025_08_28_122703_add_verification_columns_to_surat_ktp_table` - Add verification to KTP
- `2025_08_31_032420_add_verification_stages_to_surat_tables_fix` - Add verification stages to all surat tables
- `2025_08_29_150629_add_approved_status_to_enum_tables` - Add approved status option

#### Surat Usaha Improvements  
- `2025_08_28_233052_add_lama_usaha_and_omzet_usaha_to_surat_usaha_table` - Add business duration and revenue
- `2025_08_31_024144_make_tanggal_mulai_usaha_nullable_in_surat_usaha_table` - Make start date optional
- `2025_08_31_061141_add_file_columns_to_surat_usaha_table` - Add file upload fields

#### User Management
- `2025_08_31_075703_add_nik_to_users_table` - Add NIK (National ID) to users
- `2025_08_31_032723_add_user_id_to_domisili_table` - Link domisili to users

#### Minor Enhancements
- `2025_08_31_084617_add_kewarganegaraan_to_surat_skck_table` - Add nationality to SKCK
- `2025_08_31_105725_add_missing_columns_to_surat_ktp_table` - Complete KTP table structure
- `2025_08_31_114142_add_no_telepon_to_belum_menikah_table` - Add phone number to belum menikah

## Removed Migrations

### August 31, 2025 - Cleanup
- `2025_08_31_061122_add_file_columns_to_surat_usaha_table` - **REMOVED** (Empty migration with no functionality)

## Notes
- All migrations are currently applied to the database
- Migration naming follows Laravel conventions with descriptive suffixes
- Recent migrations focus on enhancing verification workflows and user experience
- File upload capabilities have been added to several surat types

## Recommendations

### Migration Optimization Opportunities

For fresh installations, some migrations could be consolidated:

#### Tables with Multiple Modifications
- **surat_usaha**: 4 separate migrations could be combined into 1
  - `2025_08_28_125039_create_surat_usaha_table`
  - `2025_08_28_233052_add_lama_usaha_and_omzet_usaha_to_surat_usaha_table`
  - `2025_08_31_024144_make_tanggal_mulai_usaha_nullable_in_surat_usaha_table`
  - `2025_08_31_061141_add_file_columns_to_surat_usaha_table`

- **verification system**: 2 migrations could be unified
  - `2025_08_28_122703_add_verification_columns_to_surat_ktp_table`
  - `2025_08_31_032420_add_verification_stages_to_surat_tables_fix`

- **users table**: 2 enhancements could be merged
  - `2025_08_23_150709_add_role_to_users_table`
  - `2025_08_31_075703_add_nik_to_users_table`

#### Potential Reduction
- Current: 29 migration files
- Optimized: Could be reduced to ~21 files (8 file reduction)
- See `migration_consolidation_reference.php` for implementation details

### Best Practices
1. Consider consolidating similar table modifications in future migrations
2. Use descriptive migration names that clearly indicate the purpose
3. Always include proper rollback functionality in down() methods
4. Test migrations thoroughly before deployment
5. Review consolidation opportunities before creating fresh installations
