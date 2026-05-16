import json
import os

def load_json(filepath):
    with open(filepath, 'r', encoding='utf-8') as f:
        return json.load(f)

def main():
    # تحميل البيانات
    students = load_json('data/students.json')
    courses = load_json('data/courses.json')
    exams = load_json('data/exams.json')

    # إدخال البحث
    query = input("أدخل رقم الطالب أو جزءاً من اسمه: ").strip()
    if not query:
        print("لم تدخل شيء، انتهى البرنامج.")
        return

    # محاولة البحث
    matched_students = []
    if query.isdigit():
        # البحث المباشر بالرقم
        for student in students:
            if student['id'] == query:
                matched_students.append(student)
                break
    else:
        # البحث بالاسم (مطابقة جزئية)
        for student in students:
            if query in student['name']:
                matched_students.append(student)

    if not matched_students:
        print(f"لم يتم العثور على طالب يطابق '{query}'.")
        return

    # لو النتيجة أكثر من واحد، نطلب التحديد
    selected_student = None
    if len(matched_students) == 1:
        selected_student = matched_students[0]
    else:
        print("\nتم العثور على عدة طلاب:")
        choice = input("\nاختر الرقم المناسب: ").strip()
        try:
            idx = int(choice) - 1
            selected_student = matched_students[idx]
        except (ValueError, IndexError):
            print("اختيار غير صالح.")
            return

    # عرض بيانات الطالب بالتفصيل
    student = selected_student
    sid = student['id']
    print("\n" + "="*50)
    print("بيانات الطالب:")
    print(f"الاسم: {student['name']}")
    print(f"الرقم: {sid}")
    print(f"المواد المسجلة: {', '.join(student['courses'])}")
    print("="*50)

    if not student['courses']:
        print("لا يوجد مواد مسجلة لهذا الطالب.")
        return

    # تجميع لجان الامتحان الخاصة بهذا الطالب
    student_exams = [exam for exam in exams if sid in exam['students']]
    # نرتبهم حسب المادة ثم اللجنة
    student_exams.sort(key=lambda e: (e['course'], e['committee']))

    print("\nجدول الامتحانات:")
    for exam in student_exams:
        code = exam['course']
        course_info = courses.get(code, {})
        course_name = course_info.get('name', 'غير معروف')
        print(f"\n- {code} ({course_name})")
        print(f"  اللجنة: {exam['committee']}")
        print(f"  المكان: {exam['room']}")
        print(f"  اليوم: {exam['day']}")
        print(f"  التاريخ: {exam['date']}")
        print(f"  الفترة: {exam['period']} {exam['time']}")

    # ملاحظة: لو الطالب مسجل في مادة ولكن لا توجد لجنة له بعد (نظرياً مش هيحصل)
    registered_courses = set(student['courses'])
    examined_courses = {exam['course'] for exam in student_exams}
    missing_exams = registered_courses - examined_courses
    if missing_exams:
        print("\n⚠️ المواد التالية لا توجد لها لجان مسجلة لهذا الطالب:")
        for code in missing_exams:
            print(f"  - {code}")

if __name__ == '__main__':
    if not os.path.isdir('data'):
        print("المجلد 'data' غير موجود. تأكد من تشغيل السكريبت في المكان الصحيح.")
    else:
        main()