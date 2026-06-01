# 🐷 SBS Piggery Management System

## Overview

The **SBS Piggery Management System** is a web-based smart livestock management platform designed for small to medium-scale piggery farm operations. The system digitalizes traditional farm record-keeping and integrates environmental monitoring, automated farm operations, and livestock management into a centralized platform.

This project was developed as a capstone/thesis project to improve farm productivity, reduce manual errors, and support data-driven decision-making for piggery management.

---

## Key Features

### 🐖 Livestock Management

* Sow and gilt management
* Batch management system
* Pig population tracking
* Age and growth monitoring
* Reproductive history tracking
* Pregnancy monitoring

### 📋 Farm Records Management

* Medication records
* Vaccination records
* Feeding records
* Sales records
* Audit logs
* User activity monitoring

### 🏥 Quarantine Management

* Quarantine tracking
* Quarantine dashboard
* Quarantine floorplan assignment
* Health monitoring of isolated animals

### 🏠 Smart Pen Floorplan

* Interactive pen and floorplan management
* Pen capacity monitoring
* Pig allocation tracking
* Visual floor layout representation

### 🔍 QR Code Identification

* Batch QR code generation
* QR code scanning
* Fast livestock information retrieval

### 🌡 Environmental Monitoring

* Real-time temperature monitoring
* Real-time humidity monitoring
* Environmental status dashboard
* Sensor data visualization

### 🤖 Farm Automation

* Automatic fan control
* Automatic feeding mechanism
* ESP32 integration
* Arduino integration
* Remote monitoring and control

### 📊 Reports and Analytics

* Dashboard analytics
* Population statistics
* Feed consumption monitoring
* Batch history reports
* Reproductive performance tracking

---

## Technologies Used

### Frontend

* HTML5
* CSS3
* JavaScript
* Bootstrap

### Backend

* PHP

### Database

* MySQL

### Hardware Integration

* ESP32
* Arduino
* DHT11 Temperature and Humidity Sensor
* Servo Motor
* Cooling Fan

### Additional Libraries

* PHP QR Code Library
* Composer Dependencies

---

## System Architecture

```text
Sensors (DHT11)
        │
        ▼
     ESP32
        │
        ▼
 Web Application (PHP + MySQL)
        │
        ├── Environmental Monitoring
        ├── Batch Management
        ├── Pregnancy Monitoring
        ├── Quarantine Management
        ├── QR Code Tracking
        └── Farm Automation
```

---

## Installation Guide

### Requirements

* XAMPP / WAMP / LAMP
* PHP 7.4 or higher
* MySQL
* Composer

### Steps

1. Clone the repository

```bash
git clone https://github.com/sesuca40032/SBS-PIGGERY-MANAGEMENT-SYSTEM.git
```

2. Move the project folder into:

```text
xampp/htdocs/
```

3. Create a MySQL database.

4. Import the database file from the `database` folder.

5. Configure database credentials in the system configuration files.

6. Start Apache and MySQL from XAMPP.

7. Open:

```text
http://localhost/SBS-PIGGERY-MANAGEMENT-SYSTEM
```

---

## Research Objectives

The project aims to:

* Digitalize piggery farm operations.
* Improve livestock monitoring and management.
* Reduce manual record-keeping errors.
* Enhance productivity through automation.
* Provide real-time environmental monitoring.
* Support informed farm management decisions.

---

## Future Enhancements

* Mobile application integration
* Cloud-based data storage
* AI-powered health prediction
* Automated disease detection
* SMS and email notifications
* Advanced analytics dashboard

---

## Developers

**Jade Edmar Sesuca**
Bachelor of Science in Information Technology
University of Northern Philippines

---

## License

This project is intended for educational, research, and academic purposes.
