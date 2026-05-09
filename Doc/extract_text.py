import xml.etree.ElementTree as ET
import os

import sys
xml_path = sys.argv[1] if len(sys.argv) > 1 else r'c:\xampp\htdocs\it-ticket\doc\temp_docx\word\document.xml'

if not os.path.exists(xml_path):
    print("File not found")
    exit(1)

tree = ET.parse(xml_path)
root = tree.getroot()

# Namespaces
ns = {'w': 'http://schemas.openxmlformats.org/wordprocessingml/2006/main'}

text = []
for p in root.findall('.//w:p', ns):
    paragraph_text = ""
    for t in p.findall('.//w:t', ns):
        if t.text:
            paragraph_text += t.text
    if paragraph_text.strip():
        text.append(paragraph_text)

import sys
sys.stdout.reconfigure(encoding='utf-8')

print("\n".join(text))

