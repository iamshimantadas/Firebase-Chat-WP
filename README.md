# Firebase Chat WP

A **one-to-one Firebase chat system** designed for seamless integration with **WordPress using PHP**.  
Although optimized for WordPress developers, the same coding structure can be adapted to other frameworks such as **React, Laravel, or even custom PHP applications**.

---
## 🚀 Features
- One-to-one chat system using **Firebase Realtime Database**
- Simple integration with **WordPress themes/plugins**
- Extendable to any framework (React, Laravel, custom PHP, etc.)
- Easy setup with Firebase credentials
- Real-time message syncing
---

## 📂 Core File

`templates/tpl-chat.php`

This is the primary coding file that powers the chat system.  
By customizing this file, you can easily extend or integrate the chat system into different platforms.

---

## ⚙️ Configuration

All you need to do is update your Firebase credentials inside:

```javascript
const firebaseConfig = {
  // your firebase credentials
};
```

## 📝 Setup Steps

1. **Open Firebase Console**  
   <img src="1.png" alt="Step 1" style="max-width:700px;">

2. **Create a New Project**  
   <img src="2.png" alt="Step 2" style="max-width:700px;">

3. **Access Your Firebase Project Dashboard**  
   <img src="3.png" alt="Step 3" style="max-width:700px;">

4. **Create Realtime Database**  
   - Go to **Build > Realtime Database**  
   <img src="4.png" alt="Step 4" style="max-width:700px;">

5. **Select Your Preferred Database Region**  
   <img src="5.png" alt="Step 5" style="max-width:700px;">

6. **Start in Locked Mode**  
   <img src="6.png" alt="Step 6" style="max-width:700px;">

7. **Set Database Rules**  
   <img src="7.png" alt="Step 7" style="max-width:700px;">

8. **Go to Project Settings → Create a Web App**  
   <img src="8.png" alt="Step 8" style="max-width:700px;">

9. **Enter Your App Name & Select Firebase Hosting**  
   <img src="9.png" alt="Step 9" style="max-width:700px;">

10. **Copy Firebase Config Credentials. Replace the existing ones in tpl-chat.php**  
    <img src="10.png" alt="Step 10" style="max-width:700px;">

11. **🎉 Done! Now when you start chatting, messages will appear in your Realtime Database.**  
    <img src="11.png" alt="Step 11" style="max-width:700px;">



## 📌 Notes

- This project is meant for **learning/demo purposes**.  
- For production apps, make sure to **secure your Firebase rules**.  
- Extend and customize as needed for your own framework.  

## 👉 My Social Media Links

- 🤹‍♂️ **LinkedIn**: [Shimanta Das](https://www.linkedin.com/in/shimanta-das-497167223)  
- 👹 **Facebook**: [Shimanta Das FB](https://www.facebook.com/profile.php?id=100078406112813)  
- 📸 **Instagram**: [@meshimanta](https://www.instagram.com/meshimanta/?hl=en)  
- 🐦 **Twitter**: [@Shimantadas247](https://mobile.twitter.com/Shimantadas247)  
- 📬 **Telegram**: [Microcodes Official](https://t.me/microcodesofficial)  
- 🎦 **YouTube**: [microcodes](https://youtube.com/channel/UCrbf6B0CU9x-I4bQOYbJVGw)  
