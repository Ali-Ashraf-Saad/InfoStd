import re

# قراءة الملف
with open("txtData/All.txt", "r", encoding="utf-8") as f:
    text = f.read()

# استخراج كود المادة + اسم المادة
subjects = re.findall(
    r'\(\s*([A-Z0-9]+)\s*\)\s*المقرر\s*(.+)',
    text
)

# حفظ النتائج في ملف
with open("txtData/subjects.txt", "w", encoding="utf-8") as f:
    for code, name in subjects:
        f.write(f"{code} - {name.strip()}\n")

print("تم حفظ المواد في subjects.txt")