# Database Relationship Diagram - Assessment System

## Overview
Diagram ini menunjukkan relasi antar tabel dalam sistem assessment yang mencakup sesi penilaian, assessment, peserta, dan data terkait.

## Entity Relationship Diagram (ERD)

```mermaid
erDiagram
    USERS {
        bigint id PK
        string name
        string email
        timestamp email_verified_at
        string password
        string remember_token
        timestamp created_at
        timestamp updated_at
    }

    SESI_PENILAIAN {
        bigint id PK
        string nama
        integer durasi_menit
        text catatan
        boolean aktif
        timestamp created_at
        timestamp updated_at
    }

    PENILAIAN {
        bigint id PK
        bigint sesi_penilaian_id FK
        string nama
        string jenis
        text petunjuk
        text konten
        string file_pdf
        integer durasi_menit
        integer urutan
        boolean aktif
        string model_in_tray
        timestamp created_at
        timestamp updated_at
    }

    SESI_ASSESSMENT {
        bigint id PK
        bigint sesi_penilaian_id FK
        bigint penilaian_id FK
        integer urutan
        integer durasi_default
        text instruksi_khusus
        boolean aktif
        timestamp created_at
        timestamp updated_at
    }

    PESERTA {
        bigint id PK
        string nama
        string email
        string no_telepon
        string instansi
        string jabatan
        boolean aktif
        timestamp created_at
        timestamp updated_at
    }

    ASSESSMENT_PARTICIPANT {
        bigint id PK
        bigint penilaian_id FK
        bigint peserta_id FK
        bigint sesi_penilaian_id FK
        timestamp mulai_pengerjaan
        timestamp selesai_pengerjaan
        integer skor_total
        text catatan
        string status
        timestamp created_at
        timestamp updated_at
    }

    LATIHAN_IN_TRAY {
        bigint id PK
        bigint penilaian_id FK
        bigint sesi_penilaian_id FK
        text konten_memo
        integer urutan
        boolean aktif
        timestamp created_at
        timestamp updated_at
    }

    JAWABAN_IN_TRAY {
        bigint id PK
        bigint assessment_participant_id FK
        bigint latihan_in_tray_id FK
        integer urutan_jawaban
        string prioritas
        text catatan
        timestamp created_at
        timestamp updated_at
    }

    JAWABAN_STUDI_KASUS {
        bigint id PK
        bigint assessment_participant_id FK
        text jawaban
        integer skor
        text catatan
        timestamp created_at
        timestamp updated_at
    }

    CATATAN_ROLEPLAY {
        bigint id PK
        bigint penilaian_id FK
        bigint assessment_participant_id FK
        text catatan_performa
        integer skor
        text feedback
        timestamp created_at
        timestamp updated_at
    }

    CATATAN_FGD {
        bigint id PK
        bigint penilaian_id FK
        bigint assessment_participant_id FK
        text catatan_partisipasi
        integer skor
        text feedback
        timestamp created_at
        timestamp updated_at
    }

    KEMAJUAN_PENILAIAN {
        bigint id PK
        bigint penilaian_id FK
        bigint assessment_participant_id FK
        string status
        integer progress_percentage
        timestamp last_activity
        timestamp created_at
        timestamp updated_at
    }

    ITEM_PENILAIAN {
        bigint id PK
        bigint penilaian_id FK
        string nama_item
        text deskripsi
        integer bobot
        integer skor_maksimal
        boolean aktif
        timestamp created_at
        timestamp updated_at
    }

    %% Relationships
    USERS ||--o{ SESI_PENILAIAN : creates
    
    SESI_PENILAIAN ||--o{ PENILAIAN : contains
    SESI_PENILAIAN ||--o{ SESI_ASSESSMENT : has
    SESI_PENILAIAN ||--o{ ASSESSMENT_PARTICIPANT : includes
    
    PENILAIAN ||--o{ SESI_ASSESSMENT : assigned_to
    PENILAIAN ||--o{ ASSESSMENT_PARTICIPANT : taken_by
    PENILAIAN ||--o{ LATIHAN_IN_TRAY : has_memos
    PENILAIAN ||--o{ CATATAN_ROLEPLAY : has_notes
    PENILAIAN ||--o{ CATATAN_FGD : has_notes
    PENILAIAN ||--o{ KEMAJUAN_PENILAIAN : tracks_progress
    PENILAIAN ||--o{ ITEM_PENILAIAN : has_items
    
    PESERTA ||--o{ ASSESSMENT_PARTICIPANT : participates_in
    
    ASSESSMENT_PARTICIPANT ||--o{ JAWABAN_IN_TRAY : submits_answers
    ASSESSMENT_PARTICIPANT ||--o{ JAWABAN_STUDI_KASUS : submits_answers
    ASSESSMENT_PARTICIPANT ||--o{ CATATAN_ROLEPLAY : has_notes
    ASSESSMENT_PARTICIPANT ||--o{ CATATAN_FGD : has_notes
    ASSESSMENT_PARTICIPANT ||--o{ KEMAJUAN_PENILAIAN : has_progress
    
    LATIHAN_IN_TRAY ||--o{ JAWABAN_IN_TRAY : answered_by
```

## Tabel Utama dan Fungsinya

### 1. **SESI_PENILAIAN**
- **Fungsi**: Menyimpan data sesi penilaian
- **Relasi**: One-to-Many dengan PENILAIAN, SESI_ASSESSMENT, ASSESSMENT_PARTICIPANT
- **Key Fields**: `id`, `nama`, `durasi_menit`, `aktif`

### 2. **PENILAIAN**
- **Fungsi**: Menyimpan master data assessment (studi kasus, in-tray, roleplay, FGD)
- **Relasi**: One-to-Many dengan SESI_ASSESSMENT, ASSESSMENT_PARTICIPANT, LATIHAN_IN_TRAY
- **Key Fields**: `id`, `nama`, `jenis`, `model_in_tray`

### 3. **SESI_ASSESSMENT**
- **Fungsi**: Menyimpan relasi antara sesi dan assessment dengan konfigurasi khusus
- **Relasi**: Many-to-One dengan SESI_PENILAIAN dan PENILAIAN
- **Key Fields**: `sesi_penilaian_id`, `penilaian_id`, `urutan`, `instruksi_khusus`

### 4. **PESERTA**
- **Fungsi**: Menyimpan data peserta assessment
- **Relasi**: One-to-Many dengan ASSESSMENT_PARTICIPANT
- **Key Fields**: `id`, `nama`, `email`, `instansi`

### 5. **ASSESSMENT_PARTICIPANT**
- **Fungsi**: Menyimpan data partisipasi peserta dalam assessment
- **Relasi**: Many-to-One dengan PENILAIAN, PESERTA, SESI_PENILAIAN
- **Key Fields**: `penilaian_id`, `peserta_id`, `sesi_penilaian_id`, `status`

## Tabel Khusus per Jenis Assessment

### 6. **LATIHAN_IN_TRAY**
- **Fungsi**: Menyimpan memo untuk assessment in-tray
- **Relasi**: Many-to-One dengan PENILAIAN dan SESI_PENILAIAN
- **Key Fields**: `penilaian_id`, `sesi_penilaian_id`, `konten_memo`, `urutan`

### 7. **JAWABAN_IN_TRAY**
- **Fungsi**: Menyimpan jawaban peserta untuk assessment in-tray
- **Relasi**: Many-to-One dengan ASSESSMENT_PARTICIPANT dan LATIHAN_IN_TRAY
- **Key Fields**: `assessment_participant_id`, `latihan_in_tray_id`, `prioritas`

### 8. **JAWABAN_STUDI_KASUS**
- **Fungsi**: Menyimpan jawaban peserta untuk assessment studi kasus
- **Relasi**: Many-to-One dengan ASSESSMENT_PARTICIPANT
- **Key Fields**: `assessment_participant_id`, `jawaban`, `skor`

### 9. **CATATAN_ROLEPLAY**
- **Fungsi**: Menyimpan catatan dan skor untuk assessment roleplay
- **Relasi**: Many-to-One dengan PENILAIAN dan ASSESSMENT_PARTICIPANT
- **Key Fields**: `penilaian_id`, `assessment_participant_id`, `catatan_performa`

### 10. **CATATAN_FGD**
- **Fungsi**: Menyimpan catatan dan skor untuk assessment FGD
- **Relasi**: Many-to-One dengan PENILAIAN dan ASSESSMENT_PARTICIPANT
- **Key Fields**: `penilaian_id`, `assessment_participant_id`, `catatan_partisipasi`

## Tabel Pendukung

### 11. **KEMAJUAN_PENILAIAN**
- **Fungsi**: Melacak progress peserta dalam mengerjakan assessment
- **Relasi**: Many-to-One dengan PENILAIAN dan ASSESSMENT_PARTICIPANT
- **Key Fields**: `penilaian_id`, `assessment_participant_id`, `status`, `progress_percentage`

### 12. **ITEM_PENILAIAN**
- **Fungsi**: Menyimpan item-item penilaian untuk setiap assessment
- **Relasi**: Many-to-One dengan PENILAIAN
- **Key Fields**: `penilaian_id`, `nama_item`, `bobot`, `skor_maksimal`

## Flow Data Assessment

### 1. **Setup Assessment**
```
USERS → SESI_PENILAIAN → SESI_ASSESSMENT → PENILAIAN
```

### 2. **Assignment Peserta**
```
PESERTA → ASSESSMENT_PARTICIPANT ← PENILAIAN
```

### 3. **Pengerjaan Assessment**
```
ASSESSMENT_PARTICIPANT → JAWABAN_IN_TRAY/JAWABAN_STUDI_KASUS
ASSESSMENT_PARTICIPANT → CATATAN_ROLEPLAY/CATATAN_FGD
```

### 4. **Tracking Progress**
```
ASSESSMENT_PARTICIPANT → KEMAJUAN_PENILAIAN
```

## Key Business Rules

1. **Sesi Assessment**: Satu sesi bisa memiliki multiple assessment dengan urutan tertentu
2. **Model In-Tray**: Assessment in-tray bisa menggunakan model "urutan" (drag-drop) atau "prioritas" (4 kategori)
3. **Participant Tracking**: Setiap peserta yang mengikuti assessment akan dicatat di ASSESSMENT_PARTICIPANT
4. **Progress Monitoring**: Progress peserta dilacak melalui KEMAJUAN_PENILAIAN
5. **Flexible Assessment**: Satu assessment bisa digunakan di multiple sesi dengan konfigurasi berbeda

## Index Recommendations

```sql
-- Primary indexes (already exist)
PRIMARY KEY (id) on all tables

-- Foreign key indexes
INDEX idx_penilaian_sesi_penilaian_id ON penilaian(sesi_penilaian_id);
INDEX idx_sesi_assessment_sesi_penilaian_id ON sesi_assessment(sesi_penilaian_id);
INDEX idx_sesi_assessment_penilaian_id ON sesi_assessment(penilaian_id);
INDEX idx_assessment_participant_penilaian_id ON assessment_participant(penilaian_id);
INDEX idx_assessment_participant_peserta_id ON assessment_participant(peserta_id);
INDEX idx_latihan_in_tray_penilaian_id ON latihan_in_tray(penilaian_id);
INDEX idx_latihan_in_tray_sesi_penilaian_id ON latihan_in_tray(sesi_penilaian_id);

-- Performance indexes
INDEX idx_penilaian_jenis ON penilaian(jenis);
INDEX idx_penilaian_model_in_tray ON penilaian(model_in_tray);
INDEX idx_assessment_participant_status ON assessment_participant(status);
INDEX idx_sesi_penilaian_aktif ON sesi_penilaian(aktif);
```

## Notes

- **model_in_tray**: Field khusus untuk assessment in-tray ('urutan' atau 'prioritas')
- **status**: Field untuk melacak status pengerjaan assessment
- **aktif**: Field boolean untuk soft delete/activation
- **timestamps**: Semua tabel memiliki created_at dan updated_at
- **Foreign Keys**: Menggunakan bigint untuk konsistensi dengan Laravel
