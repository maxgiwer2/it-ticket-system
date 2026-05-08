# 🧠 Project Memory: it-ticket

> This file serves as the persistent memory for the project, tracking goals, decisions, and progress across sessions.

---

## 🎯 Project Overview
- **Name**: IT Repair and Service Request System (โครงการพัฒนาระบบแจ้งซ่อมและขอใช้บริการงานเทคโนโลยีสารสนเทศ คณะแพทยศาสตร์)
- **Goal**: Develop a comprehensive IT ticketing system for the Faculty of Medicine, including requester forms, staff dashboards, and administrative management.
- **Current Focus**: Planning and architecting the system based on the provided requirements document (1.docx).

## 🛠️ Technical Stack
- **Framework**: Laravel 8 (compatible with PHP 8.0.30 on XAMPP)
- **Database**: MySQL (it_ticket_db)
- **Frontend**: Blade Templates, Tailwind CSS (via Laravel Mix/Breeze)
- **Auth**: Standalone Laravel Authentication (Breeze/Custom)
- **Project Structure**: 
  - Root: `c:\xampp\htdocs\it-ticket\`
  - Laravel Source: `it-ticket/src/`

## 📅 Session History & Progress
### 2026-05-08 (Current)
- **Task**: Analyzed requirements from `Doc/1.docx` and updated project memory.
- **Context**: Shifted focus from "Animation Talk" (previous context) to the "IT Ticket System" project for the Faculty of Medicine.
- **Achievements**:
  - [x] Initialized persistent memory system (`MEMORY.md`).
  - [x] Extracted text from `Doc/1.docx` (XML parsing method).
  - [x] Identified core requirements: Requester form, Staff Dashboard, Admin Management, Technical specs (PHP/MySQL).
  - [x] Initialized Laravel 8 project and Database (`it_ticket_db`).
  - [x] Implemented Database Schema (Migrations) and Initial Seeding.
  - [x] Built Requester Portal (Form, Status Search, Detail View) with premium design.

### Recent History (Summary)
- **Developing Animation Talk Interface**: Built the multi-step input flow (Style grid, Mood/Movement, etc.).
- **Network Security Website**: Designed a professional landing page for network security services.
- **Asset Management Security**: Updated authentication requirements to allow public viewing while securing data modifications.

## 💡 Key Decisions
- **Unified UI**: Used a single-page card layout for the Animation Talk tool to keep user flow focused.
- **PHP Backend**: Chosen for server-side API handling, likely for ease of deployment on XAMPP.

## 🎨 User Preferences
- **Language**: Thai (primary communication), English (code/technical).
- **Design Taste**: Premium, modern, rich aesthetics (gradients, micro-animations, clean typography).

## ⏭️ Next Steps
- [ ] Create a detailed implementation plan for the IT Ticket System.
- [ ] Design the database schema (MySQL).
- [ ] Scaffold the frontend UI (Requester Form and Staff Dashboard).
- [ ] Implement the PHP API for ticket submission.

---
*Last Updated: 2026-05-08 09:45 (Local Time)*
