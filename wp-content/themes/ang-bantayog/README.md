# Ang Bantayog — WordPress Theme

Converted from the static `ang_bantayog_website` prototype into a real,
installable WordPress theme.

## Install (XAMPP + WordPress)

1. Start **Apache** and **MySQL** in the XAMPP Control Panel.
2. Log into your WordPress admin (`http://localhost/<your-site>/wp-admin`).
3. Go to **Appearance → Themes → Add New → Upload Theme**.
4. Choose `ang-bantayog.zip` (this file) and click **Install Now**, then **Activate**.

That's it — activating the theme automatically creates:
- 4 categories: **Balita, Opinyon, Lathalain, Isports**
- A **Tungkol Sa Amin** (About) page using the Misyon/Bisyon/Haligi layout

## Using it

- **Write articles**: Posts → Add New. Assign each post to Balita, Opinyon,
  Lathalain, or Isports so it shows up in the matching homepage section.
  Set a **Featured Image** for each post — the design displays large photos.
- **Homepage**: automatically pulls your latest post as the big story, and
  recent posts per category into the Balita/Opinyon/Lathalain/Isports blocks.
  Until you publish real posts, it shows friendly placeholder cards instead
  of breaking.
- **Menu (optional)**: Appearance → Menus lets you build a custom nav; if you
  don't set one, the theme shows a sensible default menu automatically.
- **Site title/description**: Settings → General controls the name shown in
  the header/footer.

## Notes

- Design, colors (maroon/gold), fonts, and layout are unchanged from the
  original static site — only rebuilt using WordPress template tags so
  content is editable from the Dashboard instead of hand-edited HTML.
- The search box now submits to real WordPress search.
- `assets/article-*.svg` are used only as placeholder thumbnails until you
  upload real featured images.
