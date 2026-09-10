@workspace Act as a Senior Full-Stack Engineer. I have an existing Excel EXPORT feature for "Data Potensi KSI", but the output format does not match my Excel IMPORT Template. 

I want to refactor the EXPORT backend function so that the downloaded file matches the exact structure, column naming, and styling of the Import Template. This way, exported data can be easily re-imported or shared with a standardized format.

**1. Standardized Columns & Data Mapping:**
Update the export data mapping to strictly follow this array of headers (in this exact order). Map the database fields to these columns. If a field doesn't exist or is null, output an empty string.

1. "Tanggal Input" (Map from: `tanggal_input`, format as YYYY-MM-DD)
2. "Nama Usaha / Perusahaan" (Map from: `nama_usaha`)
3. "NPWP" (Map from: `npwp`)
4. "Segmen" (Map from: `segmen`)
5. "Uraian / Bidang Usaha" (Map from: `uraian`)
6. "Alamat Lengkap" (Map from: `alamat`)
7. "Latitude" (Map from: `latitude`)
8. "Longitude" (Map from: `longitude`)
9. "Estimasi Tenaga Kerja" (Map from: `estimasi_tk`)
10. "Estimasi Upah" (Map from: `estimasi_upah`)
11. "Estimasi Iuran" (Map from: `estimasi_iuran`)
12. "Program JKK, JKM, JHT, JP, JKP" (Map from: `programs`, join array with commas if necessary)
13. "Status Tindak Lanjut" (Map from: `status_tindak_lanjut`)
14. "Catatan" (Map from: `catatan`)

**2. Formatting & Styling (Using exceljs or similar):**
Apply the exact same styling to the Export file as we did for the Template:
* **Header Row (Row 1):** Freeze this row. Apply a solid background color (e.g., BPJS Green/Teal) with bold, white text, centered alignment.
* **Column Widths:** Auto-adjust column widths based on the header text or content length (e.g., "Nama Usaha / Perusahaan" and "Alamat Lengkap" should be wide).
* Remove the "ID" column from the export entirely, as it is not part of the template.

**Execution Plan:**
1. I have attached my current Excel Export controller/service file.
2. Please rewrite the function to implement this new column mapping and apply the styling.
3. Ensure the dates and numbers are formatted correctly in the Excel cells.