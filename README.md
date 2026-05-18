# 🐾 CampusTails

> **Campus Pet Monitoring, Adoption & Welfare System**
> A role-based platform for managing campus animals, health records, and adoption workflows with full transparency and audit control.

---

## ⚡ Status

![Status](https://img.shields.io/badge/status-active%20development-00c853?style=for-the-badge)
![Type](https://img.shields.io/badge/type-capstone%20project-7e57c2?style=for-the-badge)
![Architecture](https://img.shields.io/badge/architecture-rbac-red?style=for-the-badge)
![Stack](https://img.shields.io/badge/stack-php%20%7C%20supabase%20%7C%20mysql-2962ff?style=for-the-badge)

---

## ✨ What is CampusTails?

CampusTails is a **structured campus ecosystem for animal welfare management**.

It enables:

- Tracking campus animals in real time  
- Managing vaccination and medical records  
- Handling adoption workflows securely  
- Enabling donations and engagement  
- Enforcing role-based access control (RBAC)  
- Maintaining full system audit logs  

> Built for **transparency, control, and campus-wide animal welfare coordination**.

---

## 🧭 System Design Philosophy

> “Every action is traceable. Every role is intentional.”

CampusTails follows a strict **hierarchical RBAC model**:
Superadmin → Admin → Student → Guest
- Higher roles manage lower roles  
- Every transaction is logged  
- Sensitive operations are restricted by design  

---

## 🧱 Tech Stack

### Backend
- PHP (core application logic)
- Supabase (backend services)
- MySQL / PostgreSQL (relational database)

### Frontend
- HTML5
- CSS3
- JavaScript

### Architecture
- Role-Based Access Control (RBAC)
- UUID-based relational schema
- Audit logging system
- Modular feature design

---

## 👥 Roles & Permissions

### 🛡️ Superadmin
Full system authority.

- Manage all admins
- Generate admin access codes
- View system-wide logs
- Override system permissions
- Full database visibility

---

### 🛠️ Admin
Operational system manager.

- Manage pets (CRUD)
- Manage users
- Approve adoption requests
- Track donations
- Monitor vaccination records
- Access activity logs

---

### 🎓 Student / User
Active system participant.

- View pets
- Favorite animals
- Submit adoption requests
- Make donations
- View personal activity history

---

### 👤 Guest
Public viewer.

- Browse pet listings only
- No interaction privileges

---

## 🐕 Core Product Modules

### Pet Intelligence System
Manage complete pet profiles:

- Identity (name, species)
- Location found
- Image records
- Adoption status

---

### 💉 Health & Vaccination Engine
Structured medical tracking:

- Vaccination history
- Veterinary records
- Medication logs
- Next due schedules

---

### ❤️ Adoption Workflow Engine
End-to-end adoption pipeline:
Request → Review → Approval / Rejection
- Student application system
- Admin review panel
- Status tracking system

---

### 💰 Donation System
Support-based contribution system:

- Monetary donations
- In-kind support
- Optional pet targeting
- User-linked tracking

---

### ⭐ Engagement Layer
User interaction system:

- Favorites list
- Personal tracking
- Engagement history

---

### 📊 Audit Logging System
System-wide transparency layer:

- User actions
- Admin operations
- CRUD tracking
- Timestamped logs

---

## 🔐 Security Model

- Password hashing (bcrypt)
- Session-based authentication
- Role-based access control (RBAC)
- Admin code verification system
- Immutable activity logging

---

## 📊 System Dashboards

### 🛡️ Superadmin Console
- System-wide analytics
- Admin management
- Access code generation
- Full audit logs

---

### 🛠️ Admin Console
- Pet statistics
- Adoption pipeline
- Donation tracking
- Vaccination monitoring
- Activity logs

---

## 🗄️ Data Model Overview

**Core Entities**

- Users (`paw_users`)
- Pets
- Adoption Requests
- Donations
- Vaccination Records
- Medical History
- Activity Logs
- Admin Codes

---

## 🧭 Product Flow
Guest
↓
Browse Pets
↓
Student Account
↓
Adopt / Donate / Favorite
↓
Admin Review
↓
System Logging
↓
Superadmin Oversight

---

## 🎨 UI Philosophy

- Role-based UI rendering
- Clean admin-first dashboard design
- Soft academic color system (lavender/purple base)
- Card-driven layouts
- Minimal cognitive load interface

---

## 🚀 Roadmap

- Email notifications for adoption updates
- SMS vaccination reminders
- Advanced analytics dashboard
- QR-based pet identity system
- Mobile-first redesign
- Veterinary API integration

---

## 👨‍💻 Team

- Angela Jahziel B. Encabo  
- Jhen Niña Grace Aloyon  

---

## 🎓 Academic Context

**Information Management 1 — Capstone Project**

---

## 📄 License

Academic use only. Not intended for commercial deployment.

---

## 🐾 Closing Statement

> “Technology that connects people, systems, and animal welfare — responsibly.”

---
