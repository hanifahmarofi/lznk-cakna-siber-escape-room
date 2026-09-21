# LZNK Cakna Siber Escape Room : S.H.I.E.L.D.

An interactive digital learning platform utilizing a virtual escape room concept, designed to test and train staff readiness against increasingly complex digital threats[cite: 3, 5]. This system serves as a tactical training ground to elevate cybersecurity awareness and transform Lembaga Zakat Negeri Kedah (LZNK) staff into the organization's frontline defense, or "human firewall"[cite: 3, 4, 5].

## Tech Stack
* **Framework:** Laravel 11[cite: 5]
* **Languages:** PHP (Blade Views), JavaScript, HTML[cite: 5]
* **Styling:** Tailwind CSS[cite: 5]
* **Database:** MySQL Workbench 2.0[cite: 5]

## Features & Modules

### 1. Core Modules (Modul Teras)
A sequential series of simulation rooms where users analyze threats and make critical security decisions[cite: 4]:
* **01 Jaring Phishing:** Analyze incoming emails to identify legitimate communications versus malicious phishing attempts[cite: 4].
* **02 Pintu Brute Force:** Answer multiple-choice questions regarding password strength and database security[cite: 3, 4].
* **03 Tembok Api Manusia:** Review intercepted social engineering attempts via SMS, WhatsApp, or phone calls and flag threats[cite: 3, 4].
* **04 Web Cermin (Mirroring Web):** Visually inspect website interfaces and URLs to differentiate between official portals and fraudulent clones[cite: 3, 4].
* **Mainframe S.H.I.E.L.D:** A rapid-fire, time-pressured final boss module where users must quickly verify cybersecurity statements to acquire the final decryption key[cite: 3, 4].

### 2. Extra Modules
* **Hab Operasi Branching Game:** Dynamic, multi-phase scenarios (e.g., IoT infrastructure hacks, ransomware) where each user decision branches into different outcomes and consequences[cite: 3, 4].
* **Arked Permainan Mini:** Gamified cybersecurity challenges including Cyber Chess, Falling Dominoes, Connect the Dots, Word Scramble, and Video ABCD analysis[cite: 3, 4].

## System Access & Roles

The platform is divided into three primary access tiers:

### Staff / Agent
Users register or log in to the S.H.I.E.L.D secure portal to execute active operations[cite: 4]. Agents are given a set number of lives (e.g., 3 mistakes per module) and a time limit to neutralize threats[cite: 3, 4]. Successfully completing the core modules unlocks a downloadable achievement certificate[cite: 4].

### Administrator
Admins gain access to the Director Monitoring Dashboard (`/admin`) to oversee the organization's cyber readiness[cite: 3]. Features include:
* **Intel Gallery Manager:** Upload and edit cybersecurity awareness posters[cite: 3].
* **Module Configuration:** Add, edit, or delete questions, branching paths, and multimedia across all game modules[cite: 3].
* **Performance Analytics:** Track total registered staff, view active players, and monitor completion rates through visual graphs (accuracy ratios, top operatives)[cite: 3].
* **Data Export:** Generate and export detailed audit trails and performance summaries[cite: 3].

### Root / Developer
Root users manage the backend infrastructure housed within the Laravel directories (`Models`, `Controllers`, `Database Migrations`, `Views`, and `Routes`)[cite: 5]. Root access allows for database seeding, structural application updates, and executing direct MySQL queries to manually grant Admin privileges (`UPDATE shield_db.users SET is_admin = 1`)[cite: 5].

---
*Prepared by Ahmad Hanif Bin Ahmarofi*[cite: 3, 4, 5]