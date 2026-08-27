from pathlib import Path

for path in ['language/english.php', 'language/french_france_utf-8.php']:
    p = Path(path)
    text = p.read_text()
    text = text.replace("'Added by:',,", "'Added by:',")
    text = text.replace("'Map:',,", "'Map:',")
    text = text.replace("'Ajouté par :',,", "'Ajouté par :',")
    text = text.replace("'Carte :',,", "'Carte :',")
    p.write_text(text)
