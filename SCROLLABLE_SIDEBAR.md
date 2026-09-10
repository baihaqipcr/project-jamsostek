@workspace Act as an Expert Frontend Developer specializing in UI/UX. I need to improve the Sidebar component of my application. Currently, if the list of menus or nested sub-menus (like under "Daftar Potensi") grows too long, it might overflow or break the layout.

Please refactor my Sidebar to implement a clean, interactive, and scrollable experience.

**1. Scrollable Container Layout:**
* Ensure the Sidebar has a fixed full height (`h-screen`) and remains pinned to the left side of the layout.
* Split the Sidebar into 3 distinct vertical sections:
  1. **Header (Fixed at top):** Contains the App Logo and Title ("BPJS Ketenagakerjaan"). This section MUST NOT scroll.
  2. **Navigation Body (Scrollable):** The main container for all navigation links and accordions (e.g., "Daftar Potensi" and its sub-menus). Apply `overflow-y-auto` to this section.
  3. **Footer (Fixed at bottom):** Contains the User Profile info (e.g., "Baihaqi - Bidang KSI"). This section MUST NOT scroll and should always stay at the bottom of the sidebar.

**2. Scrollbar Styling (Aesthetic):**
* Implement a custom, thin scrollbar for the "Navigation Body" so it doesn't look bulky (especially on Windows).
* Use Tailwind CSS utilities (like `scrollbar-thin`, `scrollbar-thumb-gray-400`, `scrollbar-track-transparent`) or custom CSS webkit selectors to make the scrollbar elegant. It should ideally only be visible when the user hovers over the sidebar area.

**3. Interactive & Human-Centric Touches:**
* Ensure the transition for opening/closing sub-menus (Accordion) inside the scrollable area is smooth and doesn't cause jarring layout jumps.
* Add subtle hover effects to the menu items that feel responsive but not distracting.

**Execution Plan:**
1. I will tag my current `Sidebar` component file.
2. Please provide the updated code focusing on the flexbox/grid layout that separates the Fixed Header, Scrollable Body, and Fixed Footer.
3. Provide the necessary CSS or Tailwind classes to style the custom scrollbar.