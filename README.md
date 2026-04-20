# 📚 Student Courses Search System

A simple and fast web-based system to search for students and display their registered courses.

---

## 🚀 Features

* 🔍 Search by **student name** (Arabic supported with normalization)
* 🔢 Search by **academic number**
* ⚡ Fast search with limited results (Top 20)
* 🧠 Smart Arabic text normalization (handles different letter forms)
* 📊 Visitor counter (stored securely on server)
* 🔒 Backend logic hidden باستخدام PHP (no sensitive logic in frontend)

---

## 🛠️ Technologies Used

* **Frontend:** HTML, CSS, JavaScript
* **Backend:** PHP
* **Data Processing:** Python (for generating JSON data)

---

## 📁 Project Structure

```
project/
│
├── index.html        # واجهة المستخدم
├── search.php        # منطق البحث (Hidden logic)
├── counter.php       # عداد الزوار
├── students.json     # قاعدة البيانات
├── counter.txt       # تخزين عدد الزوار
│
├── generate_data.py  # سكربت تحويل ملفات TXT إلى JSON
│
├── CS.txt
├── IT.txt
├── IS.txt
├── gen.txt
```

---

## ⚙️ How It Works

### 1. Data Preparation

* يتم قراءة ملفات المواد (`.txt`)
* استخراج:

  * اسم الطالب
  * الرقم الأكاديمي
  * المواد
* حفظ البيانات في ملف:

```
students.json
```

---

### 2. Search Process

* المستخدم يكتب في مربع البحث
* يتم إرسال الطلب إلى:

```
search.php
```

#### داخل السيرفر:

* لو المدخل رقم → يبحث في الأرقام
* لو نص → يتم:

  * تطبيع الحروف العربية
  * البحث في الأسماء

---

### 3. Visitor Counter

* عند أول عملية بحث:

  * يتم استدعاء:

```
counter.php?action=increment
```

* يتم تحديث العداد في:

```
counter.txt
```

---

## ▶️ How to Run

### 1. تشغيل السيرفر (PHP)

```bash
php -S localhost:8000
```

ثم افتح:

```
http://localhost:8000
```

---

### 2. توليد البيانات (اختياري)

```bash
python generate_data.py
```

---

## 🔒 Security Notes

* لا يوجد أي API ظاهر للمستخدم
* تم نقل كل المنطق إلى السيرفر
* حماية من:

  * سرقة البيانات
  * التلاعب بالعداد

---

## 💡 Future Improvements

* ⚡ استخدام قاعدة بيانات بدل JSON
* 🔎 دعم البحث المركب (اسم + رقم)
* 📈 تحسين الأداء باستخدام indexing
* 🌐 API خاص بالتطبيقات الخارجية

---

## 👨‍💻 Author

Developed by **Ali Ashraf**

---

## ⭐ License

This project is open-source and free to use.
