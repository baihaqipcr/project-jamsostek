@workspace Act as an Expert Frontend Developer. I am working in a collaborative team environment and I am solely responsible for the "Daftar Potensi" feature. To avoid cluttering the sidebar and conflicting with other team members' work, I need to group my features into a collapsible sub-menu.

Please refactor my Sidebar component with the following requirements:

**1. Logo Update:**
* Replace the current generic cube icon at the top left of the sidebar.
* Use an image element pointing to `/img/jamsostek.jpg` (located in the `public` directory). 
* Add appropriate styling (e.g., `h-8 w-auto object-contain bg-white rounded p-1`) so the logo looks clear against the dark green background.

**2. Collapsible Menu (Accordion) Logic:**
* Convert the main "Daftar Potensi" button into a parent toggle button.
* Add a chevron icon (using Lucide or Heroicons) on the right side of "Daftar Potensi" that rotates when opened/closed.
* Implement local state (e.g., `isOpen` using `useState` in React/Vue) to handle the toggle mechanism cleanly without affecting global states.

**3. Sub-menu Nesting:**
* Move the following items to be nested *inside* the "Daftar Potensi" collapsible section:
  - Tambah Potensi
  - Impor Potensi
  - Profil Petugas
  - Export Excel
* **Styling:** Give these sub-menu items a slight indentation (left padding), a slightly smaller font size, or a different hover effect to clearly establish the visual hierarchy that they belong to "Daftar Potensi".

**Execution Plan:**
1. I will tag my Sidebar component file.
2. Please provide the updated Sidebar code ensuring all my team's routing remains intact and my features are cleanly encapsulated in the new dropdown.