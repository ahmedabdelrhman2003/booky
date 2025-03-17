# Booky

## 📖 About Booky
Booky is a multi-platform **e-book and audiobook marketplace**, offering both a **website** and a **mobile application**. The platform enables users to purchase and read digital books while also providing an **AI-powered audiobook generation system**.

### 🚀 Key Features
- **E-Book & Audiobook Sales**: Users can purchase and access digital books in text and audio formats.
- **Two Dashboards**:
  - **Admin Dashboard**: Manages users, orders, inventory, and analytics.
  - **Author Dashboard**: Allows authors to upload, manage, and track their books.
- **AI & Machine Learning Integration**: Uses **Django-based microservices** for AI-generated audiobooks.
- **Laravel & Filament-Based Backend**: Ensures efficient API handling, user management, and secure transactions.
- **Microservices Architecture**: A separate AI service built with **Django** for audiobook generation.
- **Mobile App & Web Support**: Booky is accessible via a web interface and a dedicated mobile application.

## 🛠️ Tech Stack
### **Backend**
- **Laravel** (PHP) – Main backend framework
- **Filament** – Admin panel management
- **Sanctum** – API authentication
- **Django** (Python) – Microservice for AI audiobook generation

### **Frontend & Mobile**
- **React.js** – Website frontend
- **Flutter** – Mobile application

### **Database & Storage**
- **MySQL** – Relational database
- **Redis** – Caching
- **AWS S3** / Local Storage – Book & audio file storage

### **AI & Machine Learning**
- **Django + TensorFlow/PyTorch** – AI model for audiobook conversion





## 📌 API Endpoints
| Endpoint                      | Method | Description |
|--------------------------------|--------|-------------|
| `/api/books`                  | `GET`  | Fetch all books |
| `/api/books/{id}`             | `GET`  | Get book details |
| `/api/authors/{id}/books`     | `GET`  | Get books by author |
| `/api/orders`                 | `POST` | Create a new order |
| `/api/audiobook/generate`     | `POST` | AI-powered audiobook generation |

## 📜 License
This project is licensed under the **MIT License**.

## 🤝 Contributing
We welcome contributions! Feel free to submit a pull request or open an issue.

## 📧 Contact
For inquiries, reach out at **ahmedabdelrhman297@gmail.com** 

