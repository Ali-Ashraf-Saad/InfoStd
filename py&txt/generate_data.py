import re
import json


def load_data(file_paths):
    students = {}
    current_course = None

    for file_path in file_paths:
        with open(file_path, "r", encoding="utf-8") as f:
            for line in f:
                line = line.strip()

                if not line:
                    continue

                # التعرف على المادة
                if re.match(r"^\(\s*[A-Za-z0-9]+\s*\)", line):
                    current_course = line
                    continue

                parts = line.split("\t")

                if len(parts) == 2 and current_course:
                    name = parts[0].strip()
                    number = parts[1].strip()

                    if number not in students:
                        students[number] = {
                            "name": name,
                            "courses": []
                        }

                    # منع التكرار
                    if current_course not in students[number]["courses"]:
                        students[number]["courses"].append(current_course)

    return students


# =========================
# الاستخدام
# =========================

file_paths = [
    "CS.txt",
    "IT.txt",
    "IS.txt",
    "gen.txt"
]

students = load_data(file_paths)

# حفظ JSON
with open("students.json", "w", encoding="utf-8") as f:
    json.dump(students, f, ensure_ascii=False, indent=2)

print("تم إنشاء ملف students.json بنجاح ✅")