# 🐾 CampusTails – Campus Pet Monitoring System

## 📌 Overview
CampusTails is a role-based Campus Pet Monitoring System designed to track, manage, and monitor pets within a campus environment. It provides a centralized platform for viewing pet information, maintaining health records, and managing user activity through an audit logging system.

The system supports both monitoring and engagement features, allowing students to interact with campus pets while administrators maintain system control.

---

## 🎯 Objectives
- Track and manage campus pets efficiently  
- Maintain complete vaccination and health records  
- Provide role-based access control  
- Enable administrators to manage users and monitor system activity  
- Allow students to engage through adoption, donations, and favorites  
- Ensure transparency through activity logging (audit trail)  

---

## 👥 User Roles

### 👤 Guest
- View pet listings and basic profiles  
- Limited access to system features  
- Cannot create accounts or interact with pets  

---

### 🎓 Student
- Create and manage their own accounts  
- View full pet profiles and health records  
- Favorite pets  
- Donate to support pets  
- Apply for pet adoption *(exclusive to students)*  
- Access personal profile  

---

### 🛠️ Administrator
- Full system access  
- Manage pets (Create, Read, Update, Delete)  
- Manage users (Student/Admin roles)  
- View and monitor activity logs  

---

## 🧩 Core Features

### 🐕 Pet Management
- Add, update, and delete pet profiles (Admin only)  
- View pet details:
  - Name  
  - Species  
  - Description  
  - Date Found  
  - Health Status  
  - Location Found  

---

### 💉 Health Records
- Track vaccination history  
- Maintain:
  - Last Vaccine Date  
  - Next Due Date  
  - Veterinarian Information  
- Displays full vaccination timeline  

---

### ❤️ Student Interaction Features
- **Favorites**: Save preferred pets  
- **Donations**: Support pet care efforts  
- **Adoption Requests**: Apply to adopt pets *(students only)*  

---

### 👥 User Management (Admin Only)
- View all users in a list or table  
- Assign roles (Student/Admin)  
- Reset passwords  
- Delete users  
- Create new accounts if needed  

---

### 📋 Activity Logs (Admin Only)
- Tracks system changes  
- Records:
  - User (who performed action)  
  - Action (CREATE, UPDATE, DELETE)  
  - Target (affected pet)  
  - Timestamp  

---

### 📊 Dashboard ("PawCenterbase")
Displays system statistics:
- Registered Pets  
- Vaccinated Pets  
- Pets Under Observation  
- Activity Logs count  

Admin tools include:
- Add Pet  
- Vaccination Records  
- User Management  
- Activity Logs  

---

## 🧭 Navigation Structure

### Guest
Home | Pets | Login  

### Student
Home | Pets | Favorites | Profile | Logout  

### Admin
Home | Pets | Users | Activity Logs | Profile | Logout  

---

## 🖥️ System Pages
- Landing Page  
- Dashboard (Admin Only)  
- Pet Gallery  
- Pet Profile & Health Records  
- Favorites (Student)  
- Adoption Requests (Student)  
- User Management (Admin)  
- Activity Log (Admin)  

---

## 🔐 Access Control
- Guests: View-only access with limitations  
- Students: Interactive access (favorites, donations, adoption)  
- Admins: Full CRUD functionality and system control  

---

## 🎨 UI/UX Design Principles
- Role-based visibility of components  
- Clean and professional layout  
- Consistent purple/lavender theme  
- Structured layouts (tables, dashboards, cards)  

---

## 🗂️ Data Entities

### 🐕 Pet
- Name  
- Species  
- Description  
- Date Found  
- Health Status  
- Location Found  

### 👤 User
- Name  
- Email  
- Role (Student/Admin)  

### 📋 Activity Log
- User  
- Action  
- Target Pet  
- Timestamp  

---

## ⚙️ System Requirements

### Functional Requirements
- Role-based authentication  
- Pet CRUD operations  
- User account creation (Students & Admins)  
- Favorites, donation, and adoption system  
- Activity logging system  
- Vaccination record tracking  

### Non-Functional Requirements
- Responsive UI  
- Secure access control  
- Scalable database structure  
- Intuitive interface  

---

## 🚀 Future Improvements
- Vaccination reminders and notifications  
- Search and filter functionality  
- Improved mobile responsiveness  
- Analytics dashboard  
- Integration with veterinary services  

---

## 📣 Notes
- Students can create accounts and interact with pets  
- Adoption feature is exclusive to students  
- Guests are limited to viewing only  
- System balances monitoring and student engagement  

---

## 👨‍💻 Developers
This system was developed by:
- **Angela Jahziel B. Encabo**
- **Jhen Niña Grace Aloyon**

---

## 🎓 Academic Submission
This project is submitted as part of the requirements for the **Information Management 1 Capstone Project**.

---

## 📄 License
This project is for academic purposes only.
