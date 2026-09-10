@workspace Act as an Expert Frontend Developer. I want to improve the summary cards ("Potensi aktif" and "Estimasi iuran") on my Dashboard. 

Currently, they only show totals for the current paginated page. I need to add a dropdown or selector so users can change the calculation mode dynamically between: "Keseluruhan" (All Data), "Halaman Ini" (Current Page), and "Sesuai Filter" (Filtered Data).

**1. UI Component Update:**
* Add a small, clean Dropdown/Select element inside the "Potensi aktif" and "Estimasi iuran" cards (preferably top-right corner).
* Dropdown Options: 
  - "Halaman Ini" (Default)
  - "Sesuai Filter"
  - "Keseluruhan"
* Create a state variable (e.g., `calculationMode`) to track the user's selection.
* Update the subtitle text of the card dynamically based on the selection (e.g., changing from "di halaman saat ini" to "dari total keseluruhan").

**2. Calculation Logic (Frontend):**
* **Halaman Ini:** Calculate the count and sum of `estimasi_iuran` directly from the currently displayed paginated `data` array.
* **Sesuai Filter:** Display the aggregated totals based on the active filters (Segmen, Status). This data must come from the backend API response metadata.
* **Keseluruhan:** Display the grand total of all records in the database, ignoring all filters and pagination. This must also come from the backend.

**3. Backend API Update (Crucial):**
* Review my current backend controller for this page.
* Modify the response payload to include an aggregations object. It should return not just the paginated items, but also the global totals and filtered totals.
  Example payload needed:
  `{ data: [...], aggregates: { total_count_all, total_iuran_all, total_count_filtered, total_iuran_filtered } }`

**Execution Plan:**
1. I will tag my Dashboard UI file and the Backend Controller handling this list.
2. Please provide the updated Backend Controller code first to ensure the aggregates are passed to the frontend.
3. Then, provide the updated React/Vue Dashboard code with the new dropdown state and dynamic rendering logic.