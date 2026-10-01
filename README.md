# LZNK Cakna Siber Escape Room : S.H.I.E.L.D.

An interactive digital learning platform utilizing a virtual escape room concept, designed to test and train staff readiness against increasingly complex digital threats[cite: 3, 5]. This system serves as a tactical training ground to elevate cybersecurity awareness and transform Lembaga Zakat Negeri Kedah (LZNK) staff into the organization's frontline defense, or "human firewall".

## Tech Stack
* **Framework:** Laravel 11
* **Languages:** PHP (Blade Views), JavaScript, HTML
* **Styling:** Tailwind CSS
* **Database:** MySQL Workbench 2.0

## Features & Modules

### 1. Core Modules (Modul Teras)
A sequential series of simulation rooms where users analyze threats and make critical security decisions:
* **01 Jaring Phishing:** Analyze incoming emails to identify legitimate communications versus malicious phishing attempts.
* **02 Pintu Brute Force:** Answer multiple-choice questions regarding password strength and database security.
* **03 Tembok Api Manusia:** Review intercepted social engineering attempts via SMS, WhatsApp, or phone calls and flag threats.
* **04 Web Cermin (Mirroring Web):** Visually inspect website interfaces and URLs to differentiate between official portals and fraudulent clones.
* **Mainframe S.H.I.E.L.D:** A rapid-fire, time-pressured final boss module where users must quickly verify cybersecurity statements to acquire the final decryption key.

### 2. Extra Modules
* **Hab Operasi Branching Game:** Dynamic, multi-phase scenarios (e.g., IoT infrastructure hacks, ransomware) where each user decision branches into different outcomes and consequences.
* **Arked Permainan Mini:** Gamified cybersecurity challenges including Cyber Chess, Falling Dominoes, Connect the Dots, Word Scramble, and Video ABCD analysis.

## System Access & Roles

The platform is divided into three primary access tiers:

### Staff / Agent
Users register or log in to the S.H.I.E.L.D secure portal to execute active operations[cite: 4]. Agents are given a set number of lives (e.g., 3 mistakes per module) and a time limit to neutralize threats. Successfully completing the core modules unlocks a downloadable achievement certificate.

### Administrator
Admins gain access to the Director Monitoring Dashboard (`/admin`) to oversee the organization's cyber readiness. Features include:
* **Intel Gallery Manager:** Upload and edit cybersecurity awareness posters.
* **Module Configuration:** Add, edit, or delete questions, branching paths, and multimedia across all game modules.
* **Performance Analytics:** Track total registered staff, view active players, and monitor completion rates through visual graphs (accuracy ratios, top operatives).
* **Data Export:** Generate and export detailed audit trails and performance summaries.

### Root / Developer
Root users manage the backend infrastructure housed within the Laravel directories (`Models`, `Controllers`, `Database Migrations`, `Views`, and `Routes`)[cite: 5]. Root access allows for database seeding, structural application updates, and executing direct MySQL queries to manually grant Admin privileges (`UPDATE shield_db.users SET is_admin = 1`).

---
*Prepared by Ahmad Hanif Bin Ahmarofi*
