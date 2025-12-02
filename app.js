// Enhanced Gaming Cafe Management Application - FINAL COMPLETE FIXED VERSION
class GameCafeApp {
  constructor() {
    this.currentUser = null;
    this.currentBranch = null;
    this.currentSection = "dashboard";
    this.currentView = "daily";
    this.currentConsoleId = null;
    this.currentItemId = null;
    this.selectedFoodItems = [];
    this.timers = {};
    this.charts = { main: null, peakHour: null, customerTrend: null, revenueSplit: null, consoleUtilization: null };
    this.currentSearchQuery = "";
    this.currentSort = { key: null, direction: "asc" }; // for sorting

    // Load data from provided JSON
    this.loadInitialData();
    this.init();
  }

  showMessage(id, text, type = "success") {
    const el = document.getElementById(id);
    if (!el) return;
    el.textContent = text;
    el.className = `form-message ${type}`;
  }

  loadInitialData() {
    // Load from the provided application data
    this.branches = [
      { id: 1, name: "Gamebot Central", location: "Downtown", address: "123 Gaming Street, City Center", contact: "+1234567890", manager: "John Smith", timing: "10:00 AM - 12:00 AM", isActive: true, managerEmail: "john@gamebot.com", established: "2023-01-15", console: 6 },
      { id: 2, name: "Gamebot Mall", location: "Shopping Mall", address: "456 Mall Avenue, Shopping District", contact: "+1234567891", manager: "Jane Doe", timing: "11:00 AM - 11:00 PM", isActive: true, managerEmail: "jane@gamebot.com", established: "2023-06-20", console: 8 },
    ];

    this.systemUsers = [
      { id: 1, username: "superadmin", password: "admin123", fullName: "Super Administrator", email: "super@gamebot.com", phone: "+1234567890", role: "Super Admin", branch: 1, isActive: true, lastLogin: "2025-08-24T02:30:00", joinDate: "2023-01-01", age: 35, address: "123 Admin Street", aadhar: "1234-5678-9012", workRole: "System Administrator", dutyTiming: "24/7" },
      { id: 2, username: "admin", password: "admin123", fullName: "Branch Admin", email: "admin@gamebot.com", phone: "+1234567891", role: "Admin", branch: 1, isActive: true, lastLogin: "2025-08-24T01:15:00", joinDate: "2023-01-15", age: 28, address: "456 Manager Ave", aadhar: "2345-6789-0123", workRole: "Branch Manager", dutyTiming: "10:00 AM - 12:00 AM" },
      { id: 3, username: "staff", password: "staff123", fullName: "Gaming Staff", email: "staff@gamebot.com", phone: "+1234567892", role: "Staff", branch: 1, isActive: true, lastLogin: "2025-08-24T00:45:00", joinDate: "2023-02-01", age: 24, address: "789 Staff Road", aadhar: "3456-7890-1234", workRole: "Gaming Attendant", dutyTiming: "2:00 PM - 10:00 PM" },
    ];

    this.consoles = [
      { id: 1, name: "Gaming PC Alpha", type: "PC", specs: "RTX 4080, i7-13700K, 32GB RAM", purchaseYear: 2024, email: "pc1@gamebot.com", primaryUser: "System Admin", location: "Zone A", isPlusAccount: true, isMaintenace: false, status: "available", branch: 1, currentSession: null },
      { id: 2, name: "Gaming PC Beta", type: "PC", specs: "RTX 4070, i5-13600K, 16GB RAM", purchaseYear: 2024, email: "pc2@gamebot.com", primaryUser: "Manager", location: "Zone A", isPlusAccount: false, isMaintenace: false, status: "occupied", branch: 1, currentSession: { customer: "Ash", startTime: Date.now() - 17000000, currentPlayers: 4, items: [{ name: "Energy Drink", quantity: 2, price: 3.99 }] } },
      { id: 3, name: "PlayStation 5 Pro", type: "PS5", specs: "PlayStation 5, 1TB SSD", purchaseYear: 2024, email: "ps5@gamebot.com", primaryUser: "Gaming Admin", location: "Zone B", isPlusAccount: true, isMaintenace: false, status: "available", branch: 1, currentSession: null },
      { id: 4, name: "Xbox Series X", type: "Xbox", specs: "Xbox Series X, 1TB Storage", purchaseYear: 2023, email: "xbox@gamebot.com", primaryUser: "Console Manager", location: "Zone B", isPlusAccount: false, isMaintenace: false, status: "available", branch: 1, currentSession: null },
    ];

    this.games = [
      { id: 1, name: "Valorant", category: "FPS", rating: 4.8, consoles: [1, 2], branch: 1, developer: "Riot Games", releaseDate: "2020-06-02" },
      { id: 2, name: "FIFA 24", category: "Sports", rating: 4.5, consoles: [1, 2, 3], branch: 1, developer: "EA Sports", releaseDate: "2023-09-29" },
      { id: 3, name: "Call of Duty", category: "FPS", rating: 4.7, consoles: [1, 2, 4], branch: 1, developer: "Activision", releaseDate: "2023-11-10" },
      { id: 4, name: "Fortnite", category: "Action", rating: 4.3, consoles: [1, 2, 3, 4], branch: 1, developer: "Epic Games", releaseDate: "2017-07-25" },
    ];

    // ✅ FIXED: Uncommented inventory with proper food items
    this.inventory = [
      { id: 1, name: "Energy Drink", category: "Beverages", costPrice: 2.0, sellingPrice: 3.99, stock: 45, expiryDate: "2025-12-31", branch: 1, supplier: "Red Bull Distribution", lowStockAlert: 10, sku: "RB001" },
      { id: 2, name: "Gaming Chips", category: "Snacks", costPrice: 1.5, sellingPrice: 2.99, stock: 30, expiryDate: "2025-10-15", branch: 1, supplier: "Frito-Lay", lowStockAlert: 8, sku: "DT001" },
      { id: 3, name: "Coca Cola", category: "Beverages", costPrice: 1.2, sellingPrice: 2.5, stock: 60, expiryDate: "2025-11-30", branch: 1, supplier: "Coca Cola Company", lowStockAlert: 15, sku: "CC001" },
      { id: 4, name: "Pizza Slice", category: "Food", costPrice: 4.5, sellingPrice: 8.99, stock: 15, expiryDate: "2025-08-25", branch: 1, supplier: "Fresh Foods Co", lowStockAlert: 5, sku: "PZ001" },
      { id: 5, name: "Coffee", category: "Beverages", costPrice: 1.8, sellingPrice: 3.5, stock: 25, expiryDate: "2025-12-15", branch: 1, supplier: "Coffee Masters", lowStockAlert: 8, sku: "CF001" },
      { id: 6, name: "Sandwich", category: "Food", costPrice: 3.2, sellingPrice: 6.99, stock: 20, expiryDate: "2025-08-26", branch: 1, supplier: "Fresh Foods Co", lowStockAlert: 5, sku: "SW001" },
    ];

    this.coupons = [
      { id: 1, name: "Happy Hour", code: "HAPPY50", description: "50% off during 2-5 PM", discountType: "percentage", discountAmount: 50, isActive: true, usageCount: 15, usageLimit: 100, validFrom: "2025-08-01", validTo: "2025-12-31", minOrderAmount: 10.0, branch: 1 },
      { id: 2, name: "Weekend Special", code: "WEEKEND20", description: "20% off on weekends", discountType: "percentage", discountAmount: 20, isActive: true, usageCount: 8, usageLimit: 50, validFrom: "2025-08-01", validTo: "2025-12-31", minOrderAmount: 15.0, branch: 1 },
      {
        id: 3,
        name: "Free Time Bonus",
        code: "FREETIME",
        description: "Play 2h get 30m free",
        discountType: "time_bonus",

        // 🔑 NEW FIELDS
        baseMinutes: 120, // must play at least 2h to unlock
        bonusMinutes: 30, // how much free time you get each cycle
        blockMinutes: 150, // base + bonus (cycle length)

        isActive: true,
        usageCount: 12,
        usageLimit: 200,
        validFrom: "2025-08-01",
        validTo: "2025-12-31",
        minOrderAmount: 25.0,
        branch: 1,
      },
    ];

    this.transactions = [
      { id: 1, slNo: 1, customerName: "Mike Wilson", console: "Gaming PC Alpha", consoleId: 1, duration: "02:30:00", amount: 45.5, gamingAmount: 37.5, foodAmount: 8.0, payment: "Cash", date: "2025-09-07", time: "14:30:00", branch: 1, staff: "Gaming Staff", couponUsed: null, segments: [{ players: 2, duration: 150, amount: 37.5 }] },
      { id: 2, slNo: 2, customerName: "Sarah Davis", console: "PlayStation 5 Pro", consoleId: 3, duration: "01:45:00", amount: 32.75, gamingAmount: 28.75, foodAmount: 4.0, payment: "UPI", date: "2025-09-07", time: "16:15:00", branch: 1, staff: "Gaming Staff", couponUsed: "HAPPY50", segments: [{ players: 1, duration: 105, amount: 28.75 }] },
      {
        id: 3,
        slNo: 3,
        customerName: "Tom Brown",
        console: "Gaming PC Beta",
        consoleId: 2,
        duration: "03:15:00",
        amount: 58.99,
        gamingAmount: 48.75,
        foodAmount: 10.24,
        payment: "Card",
        date: "2025-09-07",
        time: "19:20:00",
        branch: 1,
        staff: "Gaming Staff",
        couponUsed: null,
        segments: [
          { players: 3, duration: 120, amount: 32.0 },
          { players: 2, duration: 75, amount: 16.75 },
        ],
      },
      { id: 4, slNo: 4, customerName: "Anna Lee", console: "Xbox Series X", consoleId: 4, duration: "00:45:00", amount: 15.0, gamingAmount: 12.0, foodAmount: 3.0, payment: "Cash", date: "2025-09-07", time: "11:00:00", branch: 1, staff: "Gaming Staff", couponUsed: null, segments: [{ players: 1, duration: 45, amount: 12.0 }] },
      {
        id: 5,
        slNo: 5,
        customerName: "David Kim",
        console: "Gaming PC Alpha",
        consoleId: 1,
        duration: "01:15:00",
        amount: 25.0,
        gamingAmount: 20.0,
        foodAmount: 5.0,
        payment: "Card",
        date: "2025-09-08",
        time: "23:30:00",
        branch: 1,
        staff: "Gaming Staff",
        couponUsed: null,
        segments: [{ players: 2, duration: 75, amount: 20.0 }],
      },
      {
        id: 6,
        slNo: 6,
        customerName: "Emily Carter",
        console: "PlayStation 5 Pro",
        consoleId: 3,
        duration: "02:00:00",
        amount: 40.0,
        gamingAmount: 35.0,
        foodAmount: 5.0,
        payment: "UPI",
        date: "2025-09-07",
        time: "22:45:00",
        branch: 1,
        staff: "Gaming Staff",
        couponUsed: "WEEKEND20",
        segments: [{ players: 2, duration: 120, amount: 35.0 }],
      },
      {
        id: 6,
        slNo: 7,
        customerName: "NM",
        console: "PlayStation 5 Pro",
        consoleId: 3,
        duration: "02:00:00",
        amount: 40.0,
        gamingAmount: 35.0,
        foodAmount: 5.0,
        payment: "UPI",
        date: "2025-09-04",
        time: "14:45:00",
        branch: 1,
        staff: "Gaming Staff",
        couponUsed: "WEEKEND20",
        segments: [{ players: 2, duration: 120, amount: 35.0 }],
      },
      {
        id: 7,
        slNo: 8,
        customerName: "Test",
        console: "PlayStation 5 Pro",
        consoleId: 3,
        duration: "02:00:00",
        amount: 40.0,
        gamingAmount: 35.0,
        foodAmount: 5.0,
        payment: "UPI",
        date: "2025-08-04",
        time: "12:45:00",
        branch: 1,
        staff: "Gaming Staff",
        couponUsed: "WEEKEND20",
        segments: [{ players: 2, duration: 120, amount: 35.0 }],
      },
      {
        id: 8,
        slNo: 9,
        customerName: "Test1",
        console: "PlayStation 5 Pro",
        consoleId: 3,
        duration: "06:00:00",
        amount: 120.0,
        gamingAmount: 115.0,
        foodAmount: 5.0,
        payment: "UPI",
        date: "2025-06-04",
        time: "16:45:00",
        branch: 1,
        staff: "Gaming Staff",
        couponUsed: "WEEKEND20",
        segments: [{ players: 2, duration: 120, amount: 35.0 }],
      },
      {
        id: 9,
        slNo: 10,
        customerName: "Vlad",
        console: "Gaming PC Alpha",
        consoleId: 1,
        duration: "03:15:00",
        amount: 525.0,
        gamingAmount: 5.0,
        foodAmount: 500.0,
        payment: "Card",
        date: "2024-04-08",
        time: "22:30:00",
        branch: 1,
        staff: "Gaming Staff",
        couponUsed: null,
        segments: [{ players: 2, duration: 75, amount: 20.0 }],
      },
    ];

    this.priceManagement = {
      regularRates: {
        "1player": { "15min": 30.0, "30min": 50.0, "45min": 60.0, "60min": 80.0 },
        "2players": { "15min": 50.0, "30min": 80.0, "45min": 120.0, "60min": 140.0 },
        "3players": { "15min": 80.0, "30min": 100.0, "45min": 140.0, "60min": 180.0 },
        "4players": { "15min": 100.0, "30min": 140.0, "45min": 200.0, "60min": 240.0 },
      },
      vipRates: {
        "1player": { "15min": 200.0, "30min": 200.0, "45min": 300.0, "60min": 300.0 },
        "2players": { "15min": 200.0, "30min": 200.0, "45min": 300.0, "60min": 300.0 },
        "3players": { "15min": 200.0, "30min": 200.0, "45min": 300.0, "60min": 300.0 },
        "4players": { "15min": 200.0, "30min": 200.0, "45min": 300.0, "60min": 300.0 },
      },
      peakHours: [""],
      peakMultiplier: 1,
      weekendMultiplier: 1,
    };

    this.analytics = {
      systemUptime: "",
      todayRevenue: "",
      todayCustomers: "0",
      activeConsoles: 1,
      dailyData: "",
    };
  }
  // ✅ Add it here
  hasFullAccess() {
    const role = (this.currentUser?.role || "").toLowerCase();
    return role === "admin" || role === "manager" || role === "super admin";
  }

  init() {
    this.waitForDOM(() => {
      this.setupEventListeners();
      this.startTimers();

      // Debug: Check if food modal exists
      setTimeout(() => {
        const modal = document.getElementById("food-modal");
        if (modal) {
          console.log("Food modal found:", modal);
        } else {
          console.error("Food modal NOT found in DOM!");
        }
      }, 500);
    });
  }

  waitForDOM(callback) {
    if (document.readyState === "loading") {
      document.addEventListener("DOMContentLoaded", callback);
    } else {
      callback();
    }
  }

  // Live system uptime (currently active sessions)
  calculateSystemUptime() {
    let totalMs = 0;

    this.consoles.forEach((console) => {
      const session = console.currentSession;
      if (session && session.startTime) {
        if (session.isPaused && session.pauseStart) {
          // Only count time until pause started
          totalMs += session.pauseStart - session.startTime;
        } else {
          // Count full elapsed time for active session
          totalMs += Date.now() - session.startTime;
        }
      }
    });

    const hours = Math.floor(totalMs / 3600000);
    const mins = Math.floor((totalMs % 3600000) / 60000);

    const uptimeEl = document.getElementById("system-uptime");
    if (uptimeEl) {
      uptimeEl.textContent = `${hours}h ${mins}m`;
    }
  }

  //Calculates total system time
  calculateSystemTotalTime(view = "daily") {
    const now = new Date();

    // ✅ Filter transactions first
    const txs = this.filterVisibleTransactions(this.transactions);

    let filteredTx = [];

    if (view === "daily") {
      const today = now.toISOString().split("T")[0];
      filteredTx = txs.filter((tx) => tx.date === today);
    } else if (view === "monthly") {
      const ym = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, "0")}`;
      filteredTx = txs.filter((tx) => tx.date.startsWith(ym));
    } else if (view === "yearly") {
      const year = `${now.getFullYear()}`;
      filteredTx = txs.filter((tx) => tx.date.startsWith(year));
    }

    // ⏱️ Sum durations
    const totalMins = filteredTx.reduce((sum, tx) => {
      if (!tx.duration) return sum;
      const [hh, mm, ss] = tx.duration.split(":").map(Number);
      return sum + hh * 60 + mm + ss / 60;
    }, 0);

    const hrs = Math.floor(totalMins / 60);
    const mins = Math.round(totalMins % 60);

    this.analytics.systemTotalTime = hrs > 0 ? `${hrs}h ${mins}m` : `${mins}m`;

    const el = document.getElementById("system-total-time");
    if (el) el.textContent = this.analytics.systemTotalTime;

    console.log(`[SystemTotalTime] view=${view}, tx=${filteredTx.length}, total=${this.analytics.systemTotalTime}`);
  }

  //update peak hour
  updatePeakHours(transactions) {
    // ✅ Always filter to be safe
    const txs = this.filterVisibleTransactions(transactions || []);

    if (!txs || txs.length === 0) {
      document.getElementById("peak-hours").textContent = "No Data";
      return;
    }

    let hourlyCount = {};
    txs.forEach((tx) => {
      if (!tx.time) return; // safeguard
      const hour = tx.time.split(":")[0]; // HH:mm:ss → HH
      hourlyCount[hour] = (hourlyCount[hour] || 0) + 1;
    });

    if (Object.keys(hourlyCount).length === 0) {
      document.getElementById("peak-hours").textContent = "No Data";
      return;
    }

    const peakHour = Object.keys(hourlyCount).reduce((a, b) => (hourlyCount[a] > hourlyCount[b] ? a : b));

    document.getElementById("peak-hours").textContent = `${peakHour}:00`;
  }

  //update average session duration
  updateAverageSessionDuration(transactions) {
    // ✅ Always filter to respect role
    const txs = this.filterVisibleTransactions(transactions || []);

    if (!txs || txs.length === 0) {
      document.getElementById("avg-session").textContent = "0m";
      return;
    }

    const totalMins = txs.reduce((sum, tx) => {
      if (!tx.duration) return sum; // safeguard
      const [h, m, s] = tx.duration.split(":").map(Number);
      return sum + (h * 60 + m + s / 60);
    }, 0);

    const avgMins = totalMins / txs.length;
    const hrs = Math.floor(avgMins / 60);
    const mins = Math.round(avgMins % 60);

    document.getElementById("avg-session").textContent = hrs > 0 ? `${hrs}h ${mins}m` : `${mins}m`;
  }

  //Update utilization
  updateUtilization(transactions) {
    // ✅ Always filter
    const txs = this.filterVisibleTransactions(transactions || []);

    if (!txs || txs.length === 0) {
      document.getElementById("utilization").textContent = "0%";
      return;
    }

    const totalMinsUsed = txs.reduce((sum, tx) => {
      if (!tx.duration) return sum; // safeguard
      const [h, m, s] = tx.duration.split(":").map(Number);
      return sum + (h * 60 + m + s / 60);
    }, 0);

    const workingHours = 12; // 🔧 configurable if needed
    const maxMins = this.consoles.length * workingHours * 60;

    const utilization = Math.min(100, (totalMinsUsed / maxMins) * 100);
    document.getElementById("utilization").textContent = `${utilization.toFixed(1)}%`;
  }

  startTimers() {
    setInterval(() => {
      this.updateConsoleTimers();
      this.updateAnalytics();
    }, 1000);
  }

  setupEventListeners() {
    console.log("Setting up event listeners...");

    // Pause/Resume Session (Delegated)
    document.addEventListener("click", (e) => {
      if (e.target.classList.contains("pause-session-btn")) {
        const consoleId = parseInt(e.target.dataset.consoleId, 10);
        console.log("Pause button clicked for", consoleId); // 👈 debug
        this.pauseSession(consoleId);
      }
    });

    //Remove inventory
    document.addEventListener("click", (e) => {
      if (e.target.classList.contains("remove-item-btn")) {
        const consoleId = parseInt(e.target.dataset.consoleId);
        const itemIndex = parseInt(e.target.dataset.itemIndex);
        this.removeItemFromSession(consoleId, itemIndex);
      }
    });

    // ✅ LOGIN FORM
    const loginForm = document.getElementById("login-form");
    if (loginForm) {
      loginForm.addEventListener("submit", (e) => {
        e.preventDefault();
        e.stopPropagation();
        this.handleLogin();
      });
    }

    // ✅ PASSWORD TOGGLE
    const passwordToggle = document.getElementById("password-toggle");
    const passwordInput = document.getElementById("password");
    if (passwordToggle && passwordInput) {
      passwordToggle.addEventListener("click", (e) => {
        e.preventDefault();
        const eye = passwordToggle.querySelector(".eye-icon");
        const eyeOff = passwordToggle.querySelector(".eye-off-icon");

        if (passwordInput.type === "password") {
          passwordInput.type = "text";
          if (eye) eye.classList.add("hidden");
          if (eyeOff) eyeOff.classList.remove("hidden");
        } else {
          passwordInput.type = "password";
          if (eye) eye.classList.remove("hidden");
          if (eyeOff) eyeOff.classList.add("hidden");
        }
      });
    }

    // FORGOT PASSWORD LINK -> Open modal
    const forgotPassword = document.getElementById("forgot-password");
    if (forgotPassword) {
      forgotPassword.addEventListener("click", (e) => {
        e.preventDefault();
        e.stopPropagation();
        this.showModal("forgot-password-modal");
      });
    }

    // FORGOT PASSWORD FORM
    const forgotPasswordForm = document.getElementById("forgot-password-form");
    if (forgotPasswordForm) {
      forgotPasswordForm.addEventListener("submit", (e) => {
        e.preventDefault();
        e.stopPropagation();

        const email = document.getElementById("reset-email").value.trim();

        if (!email) {
          this.showToast("Please enter your email address.", "error");
          return;
        }

        // ✅ Search in all systemUsers, not just currentUser
        const user = this.systemUsers.find((u) => u.email && u.email.toLowerCase() === email.toLowerCase());

        if (user) {
          // Generate temporary password
          const tempPassword = Math.random().toString(36).slice(-8);

          // Update that user’s password
          user.password = tempPassword;
          user.mustChangePassword = true;

          // If the user is also the logged-in user, sync currentUser
          if (this.currentUser && this.currentUser.username === user.username) {
            this.currentUser.password = tempPassword;
            this.currentUser.mustChangePassword = false;
          }

          this.showToast("A temporary password has been sent to your email.", "success");
          console.log(`Temporary password for ${email}: ${tempPassword}`);

          // Reset form + close modal
          document.getElementById("reset-email").value = "";
          this.hideModal("forgot-password-modal");
        } else {
          this.showToast("Email not found in our records.", "error");
        }
      });
    }

    // Update Profile
    const updateBtn = document.getElementById("update-profile-btn");
    if (updateBtn) {
      updateBtn.addEventListener("click", () => {
        const name = document.getElementById("profile-name").value.trim();
        const email = document.getElementById("profile-email").value.trim();

        if (!name || !email) {
          this.showToast("Name and Email cannot be empty.", "error");
          return;
        }

        if (name === this.currentUser.fullName && email === this.currentUser.email) {
          this.showToast("No changes detected.", "warning");
          return;
        }

        // Update current user (replace with API call if needed)
        this.currentUser.fullName = name;
        this.currentUser.email = email;
        // Update top display
        const currentUserSpan = document.getElementById("current-user");
        if (currentUserSpan) {
          currentUserSpan.textContent = name;
        }

        this.showToast("Profile updated successfully!", "success");
      });
    }

    // Change Password
    const changePasswordBtn = document.getElementById("change-password-btn");
    if (changePasswordBtn) {
      changePasswordBtn.addEventListener("click", () => {
        const currentPass = document.getElementById("current-password").value.trim();
        const newPass = document.getElementById("new-password").value.trim();
        const confirmPass = document.getElementById("confirm-password").value.trim();

        if (!currentPass || !newPass || !confirmPass) {
          this.showToast("All fields are required.", "error");
          return;
        }

        if (newPass !== confirmPass) {
          this.showToast("New password and confirmation do not match.", "error");
          return;
        }

        if (currentPass !== this.currentUser.password) {
          this.showToast("Current password is incorrect.", "error");
          return;
        }

        if (newPass === currentPass) {
          this.showToast("New password cannot be the same as the current password.", "warning");
          return;
        }

        // Update password
        this.currentUser.password = newPass;

        this.showToast("Password changed successfully!", "success");

        // Clear inputs & strength meter
        document.getElementById("current-password").value = "";
        document.getElementById("new-password").value = "";
        document.getElementById("confirm-password").value = "";
        document.getElementById("password-strength").textContent = "";

        // Simulate logout after short delay
        setTimeout(() => {
          // Clear stored session/user (adjust to your app logic)
          this.currentUser = null;
          localStorage.removeItem("sessionToken"); // if you’re using tokens
          sessionStorage.clear();

          // Redirect to login
          window.location.href = "/index.html";
        }, 2000);
      });
    }

    // Password Strength Checker
    const newPassInput = document.getElementById("new-password");
    const confirmPassInput = document.getElementById("confirm-password");

    if (newPassInput) {
      newPassInput.addEventListener("input", () => {
        const val = newPassInput.value;
        const strengthEl = document.getElementById("password-strength");

        if (!val) {
          strengthEl.textContent = "";
          strengthEl.className = "password-strength";
          return;
        }

        let strength = "weak";
        if (val.length >= 8 && /[A-Z]/.test(val) && /\d/.test(val) && /[^A-Za-z0-9]/.test(val)) {
          strength = "strong";
        } else if (val.length >= 6) {
          strength = "medium";
        }

        strengthEl.textContent = `Strength: ${strength}`;
        strengthEl.className = `password-strength ${strength}`;
      });
    }

    // Confirm Password Match Checker
    if (confirmPassInput) {
      confirmPassInput.addEventListener("input", () => {
        const newVal = newPassInput.value;
        const confirmVal = confirmPassInput.value;
        const matchEl = document.getElementById("password-match");

        if (!confirmVal) {
          matchEl.textContent = "";
          matchEl.className = "password-strength";
          return;
        }

        if (newVal === confirmVal) {
          matchEl.textContent = "Passwords match ✔";
          matchEl.className = "password-strength strong";
        } else {
          matchEl.textContent = "Passwords do not match ✘";
          matchEl.className = "password-strength weak";
        }
      });
    }

    // Transaction header sort
    document.querySelectorAll("#transactions thead th[data-sort]").forEach((th) => {
      th.addEventListener("click", () => {
        const key = th.dataset.sort;

        if (this.currentSort.key === key) {
          // Toggle sort direction
          this.currentSort.direction = this.currentSort.direction === "asc" ? "desc" : "asc";
        } else {
          this.currentSort.key = key;
          this.currentSort.direction = "asc";
        }

        // Update header arrows
        document.querySelectorAll("#transactions thead th").forEach((h) => h.classList.remove("sorted-asc", "sorted-desc"));
        th.classList.add(this.currentSort.direction === "asc" ? "sorted-asc" : "sorted-desc");

        this.applyAllTransactionFilters();
      });
    });

    // global search transaction
    const globalSearch = document.getElementById("global-search");
    const clearSearchBtn = document.getElementById("clear-search");
    const clearFiltersBtn = document.getElementById("clear-filters"); // Add this

    if (globalSearch) {
      // Input typing → update search query + trigger filter
      globalSearch.addEventListener("input", (e) => {
        this.currentSearchQuery = e.target.value.trim().toLowerCase();
        clearSearchBtn.style.display = this.currentSearchQuery ? "block" : "none";
        this.applyAllTransactionFilters();
      });

      // Clear button click → reset query
      if (clearSearchBtn) {
        clearSearchBtn.addEventListener("click", () => {
          globalSearch.value = "";
          this.currentSearchQuery = "";
          clearSearchBtn.style.display = "none";
          this.applyAllTransactionFilters();
        });
      }

      // ESC key → reset query
      globalSearch.addEventListener("keydown", (e) => {
        if (e.key === "Escape") {
          globalSearch.value = "";
          this.currentSearchQuery = "";
          if (clearSearchBtn) clearSearchBtn.style.display = "none";
          this.applyAllTransactionFilters();
        }
      });

      // Clear Filters button → also reset global search
      if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener("click", () => {
          globalSearch.value = "";
          this.currentSearchQuery = "";
          if (clearSearchBtn) clearSearchBtn.style.display = "none";
          this.applyAllTransactionFilters();
        });
      }
    }

    // Cancel login
    const cancelLogin = document.getElementById("cancel-login");
    if (cancelLogin) {
      cancelLogin.addEventListener("click", (e) => {
        e.preventDefault();
        e.stopPropagation();
        this.hideModal("login-modal");
      });
    }

    // Navigation - COMMENTED OUT for multi-page PHP application
    // This was for single-page app, now we use normal link navigation
    // document.querySelectorAll('.nav-item').forEach(item => {
    //     item.addEventListener('click', (e) => {
    //         e.preventDefault();
    //         const section = item.getAttribute('data-section');
    //         this.showSection(section);
    //     });
    // });

    // Dashboard view toggles
    document.querySelectorAll(".view-toggle").forEach((btn) => {
      btn.addEventListener("click", (e) => {
        const view = btn.dataset.view;
        this.switchDashboardView(view);
      });
    });

    // Reset stats button
    const resetStatsBtn = document.getElementById("reset-stats-btn");
    if (resetStatsBtn) {
      resetStatsBtn.addEventListener("click", () => this.resetStats());
    }

    // Logout
    const logoutBtn = document.getElementById("logout-btn");
    if (logoutBtn) {
      logoutBtn.addEventListener("click", () => this.logout());
    }

    // Transaction filters
    const applyFilters = document.getElementById("apply-filters");
    if (applyFilters) {
      applyFilters.addEventListener("click", (e) => {
        e.preventDefault();
        e.stopPropagation();
        this.applyAllTransactionFilters();
      });
    }

    const exportTransactionsBtn = document.getElementById("export-transactions");
    if (exportTransactionsBtn) {
      exportTransactionsBtn.addEventListener("click", (e) => {
        e.preventDefault();
        e.stopPropagation();
        this.exportTransactions();
      });
    }

    // ✅ Add Food & Drinks button
    const addFdBtn = document.getElementById("add-fd-btn");
    if (addFdBtn) {
      addFdBtn.addEventListener("click", () => {
        this.renderFoodItems(); // build fresh grid
        this.resetFoodModal(); // reset counts + stock
        this.showModal("food-modal");
      });
    }

    // Clear filters
    const clearFilters = document.getElementById("clear-filters");
    if (clearFilters) {
      clearFilters.addEventListener("click", (e) => {
        e.preventDefault();
        e.stopPropagation();
        document.getElementById("filter-start").value = "";
        document.getElementById("filter-end").value = "";
        document.getElementById("filter-console").value = "";
        this.currentSearchQuery = "";
        this.currentSort = { key: null, direction: "asc" };
        this.applyAllTransactionFilters();
      });
    }

    // Modal close (top-right X)
    document.addEventListener("click", (e) => {
      if (e.target.classList.contains("modal-close-icon") || e.target.classList.contains("modal-cancel")) {
        const modalId = e.target.dataset.modal;
        if (modalId) {
          // Hide the modal
          this.hideModal(modalId);

          // Reset form fields inside the modal
          const modal = document.getElementById(modalId);
          if (modal) {
            const form = modal.querySelector("form");
            if (form) form.reset();
          }
        }
      }
    });

    // Transaction actions (delegated)
    document.addEventListener("click", (e) => {
      if (e.target.classList.contains("view-bill-btn")) {
        e.preventDefault();
        e.stopPropagation();
        this.viewBill(parseInt(e.target.dataset.id));
      } else if (e.target.classList.contains("print-bill-btn")) {
        e.preventDefault();
        e.stopPropagation();
        this.printBill(parseInt(e.target.dataset.id));
      }
    });

    document.querySelectorAll(".view-toggle").forEach((btn) => {
      btn.addEventListener("click", (e) => {
        e.preventDefault();
        e.stopPropagation();

        const view = btn.dataset.view;
        this.switchDashboardView(view); // ✅ updates widgets, chart, and button states
      });
    });

    // ✅ Coupon discount type toggle
    const discountSelect = document.getElementById("offer-discount-type");
    if (discountSelect) {
      discountSelect.addEventListener("change", () => {
        this.toggleDiscountFields();
      });
    }

    this.setupAddButtons();
    this.setupPriceManagementHandlers();
    this.setupModalHandlers();
    this.setupDynamicEventListeners();

    // Payment method change
    document.addEventListener("change", (e) => {
      if (e.target.name === "payment-method") {
        this.handlePaymentMethodChange(e.target.value);
      }
    });

    console.log("Event listeners setup complete");
  }

  setupAddButtons() {
    const buttons = [
      { id: "add-console-btn", handler: () => this.showAddConsoleModal() },
      { id: "add-game-btn", handler: () => this.showAddGameModal() },
      { id: "add-inventory-btn", handler: () => this.showAddInventoryModal() },
      { id: "add-offer-btn", handler: () => this.showAddOfferModal() },
      { id: "add-user-btn", handler: () => this.showAddUserModal() },
      { id: "add-branch-btn", handler: () => this.showAddBranchModal() },
      { id: "add-offer-btn", handler: () => this.showAddOfferModal() },
      //{ id: 'edit-pricing-btn', handler: () => this.showEditPricingModal() },
    ];

    buttons.forEach(({ id, handler }) => {
      const btn = document.getElementById(id);
      if (btn) {
        btn.addEventListener("click", handler);
      }
    });
  }

  setupPriceManagementHandlers() {
    // Regular rates edit form
    const editRegularRatesForm = document.getElementById("edit-regular-rates-form");
    if (editRegularRatesForm) {
      editRegularRatesForm.addEventListener("submit", (e) => {
        e.preventDefault();
        this.saveRegularRates();
      });
    }

    // VIP rates edit form
    const editVipRatesForm = document.getElementById("edit-vip-rates-form");
    if (editVipRatesForm) {
      editVipRatesForm.addEventListener("submit", (e) => {
        e.preventDefault();
        this.saveVipRates();
      });
    }

    // Peak hours edit form
    const editPeakHoursForm = document.getElementById("edit-peak-hours-form");
    if (editPeakHoursForm) {
      editPeakHoursForm.addEventListener("submit", (e) => {
        e.preventDefault();
        this.savePeakHours();
      });
    }

    // Peak multiplier edit form
    const editPeakMultiplierForm = document.getElementById("edit-peak-multiplier-form");
    if (editPeakMultiplierForm) {
      editPeakMultiplierForm.addEventListener("submit", (e) => {
        e.preventDefault();
        this.savePeakMultiplier();
      });
    }

    // Weekend multiplier edit form
    const editWeekendMultiplierForm = document.getElementById("edit-weekend-multiplier-form");
    if (editWeekendMultiplierForm) {
      editWeekendMultiplierForm.addEventListener("submit", (e) => {
        e.preventDefault();
        this.saveWeekendMultiplier();
      });
    }
  }

  setupModalHandlers() {
    // Form handlers
    const forms = [
      {
        id: "console-form",
        handler: (e) => {
          e.preventDefault();
          this.saveConsole();
        },
      },
      {
        id: "inventory-form",
        handler: (e) => {
          e.preventDefault();
          this.saveInventory();
        },
      },
      {
        id: "game-form",
        handler: (e) => {
          e.preventDefault();
          this.saveGame();
        },
      },
      {
        id: "user-form",
        handler: (e) => {
          e.preventDefault();
          this.saveUser();
        },
      },
      {
        id: "branch-form",
        handler: (e) => {
          e.preventDefault();
          this.saveBranch();
        },
      },
      {
        id: "session-form",
        handler: (e) => {
          e.preventDefault();
          this.startSession();
        },
      },
      {
        id: "change-players-form",
        handler: (e) => {
          e.preventDefault();
          this.confirmPlayerChange();
        },
      },
      {
        id: "offer-form",
        handler: (e) => {
          e.preventDefault();
          this.saveOffer();
        },
      },
      //{ id: 'pricing-form', handler: (e) => { e.preventDefault(); this.savePricing(); } },
    ];

    forms.forEach(({ id, handler }) => {
      const form = document.getElementById(id);
      if (form) form.addEventListener("submit", handler);
    });

    // Cancel buttons
    const cancelButtons = ["cancel-session", "cancel-billing", "cancel-food", "cancel-console", "cancel-inventory", "cancel-game", "cancel-user", "cancel-branch", "cancel-change-players", "cancel-transfer", "cancel-offer", "cancel-offer-2", "cancel-edit-regular-rates", "cancel-edit-vip-rates", "cancel-edit-peak-hours", "cancel-edit-peak-multiplier", "cancel-edit-weekend-multiplier"];

    cancelButtons.forEach((id) => {
      const btn = document.getElementById(id);
      if (btn) {
        btn.addEventListener("click", (e) => {
          e.preventDefault();
          const modalId = btn.closest(".modal").id;
          this.hideModal(modalId);
        });
      }
    });

    // Action buttons
    const actionButtons = [
      { id: "confirm-payment-print", handler: () => this.confirmPaymentAndPrint() },
      { id: "add-food-items", handler: () => this.addFoodItems() },
      { id: "confirm-transfer", handler: () => this.confirmTransfer() },
    ];

    actionButtons.forEach(({ id, handler }) => {
      const btn = document.getElementById(id);
      if (btn) btn.addEventListener("click", handler);
    });

    // Console type change handler
    const consoleTypeSelect = document.getElementById("console-type");
    if (consoleTypeSelect) {
      consoleTypeSelect.addEventListener("change", (e) => {
        this.updateConsoleSpecs(e.target.value);
      });
    }

    // BACKUP: Direct Add F&D button listeners (in case dynamic ones fail)
    setTimeout(() => {
      const addFoodButtons = document.querySelectorAll(".add-food-btn");
      console.log("Setting up direct Add F&D listeners for", addFoodButtons.length, "buttons");

      addFoodButtons.forEach((btn) => {
        btn.addEventListener("click", (e) => {
          e.preventDefault();
          e.stopPropagation();
          const consoleId = parseInt(btn.dataset.consoleId);
          console.log("DIRECT Add F&D clicked for console:", consoleId);

          if (consoleId) {
            this.showFoodModal(consoleId);
          }
        });
      });
    }, 1000);
  }

  setupDynamicEventListeners() {
    document.addEventListener("click", (e) => {
      // Close modal on backdrop click
      if (e.target.classList.contains("modal") && !e.target.closest(".modal-content")) {
        e.target.classList.add("hidden");
        return;
      }

      // Console actions
      if (e.target.classList.contains("start-session-btn")) {
        e.preventDefault();
        this.showSessionModal(parseInt(e.target.dataset.consoleId));
      } else if (e.target.classList.contains("end-session-btn")) {
        e.preventDefault();
        this.endSession(parseInt(e.target.dataset.consoleId));
      }
      // FIXED: Enhanced Add F&D button handling
      else if (e.target.classList.contains("add-food-btn")) {
        e.preventDefault();
        e.stopPropagation();
        const consoleId = parseInt(e.target.dataset.consoleId);
        console.log("ADD F&D BUTTON CLICKED for console:", consoleId);

        if (consoleId) {
          this.showFoodModal(consoleId);
        } else {
          console.error("Console ID not found on Add F&D button");
          this.showToast("Error: Console ID not found", "error");
        }
      } else if (e.target.classList.contains("transfer-session-btn")) {
        e.preventDefault();
        this.showTransferModal(parseInt(e.target.dataset.consoleId));
      } else if (e.target.classList.contains("change-players-btn")) {
        e.preventDefault();
        this.showChangePlayersModal(parseInt(e.target.dataset.consoleId));
      } else if (e.target.classList.contains("edit-console-btn")) {
        e.preventDefault();
        this.showEditConsoleModal(parseInt(e.target.dataset.consoleId));
      } else if (e.target.classList.contains("delete-console-btn")) {
        e.preventDefault();
        this.deleteConsole(parseInt(e.target.dataset.consoleId));
      }

      // Game actions
      else if (e.target.classList.contains("edit-game-btn")) {
        e.preventDefault();
        this.showEditGameModal(parseInt(e.target.dataset.gameId));
      } else if (e.target.classList.contains("delete-game-btn")) {
        e.preventDefault();
        this.deleteGame(parseInt(e.target.dataset.gameId));
      }

      // Inventory actions
      else if (e.target.classList.contains("edit-inventory-btn")) {
        e.preventDefault();
        this.showEditInventoryModal(parseInt(e.target.dataset.itemId));
      } else if (e.target.classList.contains("delete-inventory-btn")) {
        e.preventDefault();
        this.deleteInventoryItem(parseInt(e.target.dataset.itemId));
      }

      // Offer actions
      else if (e.target.classList.contains("edit-offer-btn")) {
        e.preventDefault();
        this.showEditOfferModal(parseInt(e.target.dataset.offerId));
      } else if (e.target.classList.contains("delete-offer-btn")) {
        e.preventDefault();
        this.deleteOffer(parseInt(e.target.dataset.offerId));
      } else if (e.target.classList.contains("toggle-offer-btn")) {
        e.preventDefault();
        this.toggleOffer(parseInt(e.target.dataset.offerId));
      }

      // User actions
      else if (e.target.classList.contains("edit-user-btn")) {
        e.preventDefault();
        this.showEditUserModal(parseInt(e.target.dataset.userId));
      } else if (e.target.classList.contains("delete-user-btn")) {
        e.preventDefault();
        this.deleteUser(parseInt(e.target.dataset.userId));
      } else if (e.target.classList.contains("toggle-user-btn")) {
        e.preventDefault();
        this.toggleUserStatus(parseInt(e.target.dataset.userId));
      } else if (e.target.classList.contains("delete-transaction-btn")) {
        e.preventDefault();
        this.deleteTransaction(parseInt(e.target.dataset.id));
      }

      // Branch actions
      else if (e.target.classList.contains("edit-branch-btn")) {
        e.preventDefault();
        this.showEditBranchModal(parseInt(e.target.dataset.branchId));
      } else if (e.target.classList.contains("delete-branch-btn")) {
        e.preventDefault();
        this.deleteBranch(parseInt(e.target.dataset.branchId));
      }

      // Console transfer selection
      else if (e.target.closest(".console-transfer-item")) {
        const transferItem = e.target.closest(".console-transfer-item");
        document.querySelectorAll(".console-transfer-item").forEach((item) => {
          item.classList.remove("selected");
        });
        transferItem.classList.add("selected");
        this.selectedTransferConsoleId = parseInt(transferItem.dataset.consoleId);
      }

      // Price Management Edit Actions
      else if (e.target.classList.contains("edit-regular-rates-btn")) {
        e.preventDefault();
        e.stopPropagation();
        const playerKey = e.target.dataset.playerKey;
        this.showEditRegularRatesModal(playerKey);
      } else if (e.target.classList.contains("edit-vip-rates-btn")) {
        e.preventDefault();
        e.stopPropagation();
        const playerKey = e.target.dataset.playerKey;
        this.showEditVipRatesModal(playerKey);
      } else if (e.target.classList.contains("edit-peak-hours-btn")) {
        e.preventDefault();
        e.stopPropagation();
        this.showEditPeakHoursModal();
      } else if (e.target.classList.contains("edit-peak-multiplier-btn")) {
        e.preventDefault();
        e.stopPropagation();
        this.showEditPeakMultiplierModal();
      } else if (e.target.classList.contains("edit-weekend-multiplier-btn")) {
        e.preventDefault();
        e.stopPropagation();
        this.showEditWeekendMultiplierModal();
      }
    });
  }

  clearLoginForm() {
    const usernameInput = document.getElementById("username");
    const passwordInput = document.getElementById("password");

    if (usernameInput) {
      usernameInput.value = "";
      setTimeout(() => usernameInput.focus(), 200);
    }
    if (passwordInput) {
      passwordInput.value = "";
    }
  }

  handleLogin() {
    const usernameInput = document.getElementById("username");
    const passwordInput = document.getElementById("password");

    if (!usernameInput || !passwordInput) {
      this.showToast("Login form not found", "error");
      return;
    }

    const username = usernameInput.value.trim();
    const password = passwordInput.value.trim();

    console.log("Attempting login with:", username);

    const user = this.systemUsers.find((u) => u.username === username && u.password === password);

    if (user) {
      this.currentUser = user;

      // Must change password first
      if (user.mustChangePassword) {
        this.showModal("change-password-modal");
        this.showToast("You must create a new password before continuing.", "warning");
        return;
      }

      // ✅ Hide login screen
      const loginScreen = document.getElementById("login-screen");
      if (loginScreen) loginScreen.style.display = "none";

      // ✅ Show main app
      const mainApp = document.getElementById("main-app");
      if (mainApp) {
        mainApp.classList.remove("hidden");
        mainApp.style.display = "grid";
      }

      this.showToast(`Welcome to GameBot Gaming Cafe, ${user.fullName}!`, "success");
      this.logActivity(`🔑 User ${user.fullName} logged in`);

      setTimeout(() => {
        this.showSection("dashboard");
        this.switchDashboardView("daily");
        this.renderDashboard();
      }, 100);

      // Make sure login modal is hidden too
      this.hideModal("login-modal");

      // Show/hide staff-only stuff
      if (user.role === "Staff") {
        document.body.classList.add("staff-user");
      } else {
        document.body.classList.remove("staff-user");
      }

      user.lastLogin = new Date().toISOString();

      // Load dashboard
      setTimeout(() => {
        this.showSection("dashboard");
        this.switchDashboardView("daily");
        this.renderDashboard();
      }, 100);

      this.showToast(`Welcome to GameBot Gaming Cafe, ${user.fullName}!`, "success");
      this.logActivity(`🔑 User ${user.fullName} logged in`);
    } else {
      console.log("Login failed for:", username);
      this.showToast("Invalid username or password!", "error");
    }
  }

  showModal(id) {
    const modal = document.getElementById(id);
    if (modal) modal.classList.remove("hidden");
  }

  hideModal(id) {
    const modal = document.getElementById(id);
    if (modal) modal.classList.add("hidden");
  }

  applyAllTransactionFilters() {
    let result = [...this.transactions];

    // Apply filters (if you already have applyTransactionFilters logic, merge it here)
    const startDate = document.getElementById("filter-start")?.value;
    const endDate = document.getElementById("filter-end")?.value;
    const consoleFilter = document.getElementById("filter-console")?.value;

    if (startDate) {
      result = result.filter((tx) => new Date(tx.date) >= new Date(startDate));
    }
    if (endDate) {
      result = result.filter((tx) => new Date(tx.date) <= new Date(endDate));
    }
    if (consoleFilter) {
      result = result.filter((tx) => tx.consoleId == consoleFilter);
    }

    // Apply search
    if (this.currentSearchQuery) {
      const query = this.currentSearchQuery;

      result = result.filter((tx) => {
        return Object.values(tx).some((val) => String(val).toLowerCase().includes(query));
      });
    }

    // Apply sort
    if (this.currentSort.key) {
      result.sort((a, b) => {
        const key = this.currentSort.key;
        let valA, valB;

        switch (key) {
          case "slNo":
            // sort by transaction id (newest = highest id)
            valA = a.id;
            valB = b.id;
            break;
          case "customerName":
            valA = a.customerName.toLowerCase();
            valB = b.customerName.toLowerCase();
            break;
          case "foodAmount":
            valA = a.foodAmount;
            valB = b.foodAmount;
            break;
          case "gamingAmount":
            valA = a.gamingAmount;
            valB = b.gamingAmount;
            break;
          case "amount":
            valA = a.amount;
            valB = b.amount;
            break;
          case "payment":
            valA = a.payment.toLowerCase();
            valB = b.payment.toLowerCase();
            break;
          case "staff":
            valA = a.staff.toLowerCase();
            valB = b.staff.toLowerCase();
            break;
          default:
            valA = a[key];
            valB = b[key];
        }

        if (typeof valA === "number" && typeof valB === "number") {
          return this.currentSort.direction === "asc" ? valA - valB : valB - valA;
        } else {
          return this.currentSort.direction === "asc" ? String(valA).localeCompare(String(valB)) : String(valB).localeCompare(String(valA));
        }
      });
    }

    this.renderTransactions(result);
  }

  logout() {
    if (this.currentUser) {
      this.logActivity(`🚪 User ${this.currentUser.fullName} logged out`);
    }

    this.currentUser = null;
    this.currentView = "daily";

    const loginScreen = document.getElementById("login-screen");
    const mainApp = document.getElementById("main-app");

    if (loginScreen) loginScreen.style.display = "flex";
    if (mainApp) {
      mainApp.classList.add("hidden");
      mainApp.style.display = "none";
    }

    document.body.classList.remove("staff-user");
    this.clearLoginForm();
    this.showToast("Logged out successfully", "info");
  }

  showSection(sectionName) {
    document.querySelectorAll(".nav-item").forEach((item) => {
      item.classList.remove("active");
    });

    const activeNavItem = document.querySelector(`[data-section="${sectionName}"]`);
    if (activeNavItem) {
      activeNavItem.classList.add("active");
    }

    document.querySelectorAll(".content-section").forEach((section) => {
      section.classList.remove("active");
    });

    const targetSection = document.getElementById(sectionName);
    if (targetSection) {
      targetSection.classList.add("active");
    }

    this.currentSection = sectionName;

    // ✅ Force daily view whenever dashboard is opened
    if (sectionName === "dashboard") {
      this.currentView = "daily";
    }

    setTimeout(() => {
      this.renderSection(sectionName);
    }, 50);
  }

  renderSection(sectionName) {
    switch (sectionName) {
      case "dashboard":
        this.renderDashboard();
        break;
      case "console-mapping":
        this.renderConsoles();
        break;
      case "transactions":
        this.renderTransactions();
        break;
      case "games":
        this.renderGames();
        break;
      case "inventory":
        this.renderInventory();
        break;
      case "offers":
        this.renderOffers();
        break;
      case "price-management":
        this.renderPriceManagement();
        break;
      case "user-management":
        this.renderUserManagement();
        break;
      case "branch-management":
        this.renderBranchManagement();
        break;
      case "profile":
        this.renderProfile();
        break;
    }
  }
  switchDashboardView(view) {
    this.currentView = view;

    // ✅ Get filtered transactions once
    const txs = this.filterVisibleTransactions(this.transactions);

    // 🔄 Update widgets and charts with filtered txs
    this.updateWidgetsByView(view, txs);
    this.updateDashboardChart(view, txs);
    this.updatePeakHourChart(view, txs);
    this.updateCustomerTrendChart(view, txs);
    this.updateRevenueSplitChart(view, txs);
    this.updateConsoleUtilizationChart(view, txs);

    // 🎨 Update buttons visually
    document.querySelectorAll(".view-toggle").forEach((btn) => {
      btn.classList.toggle("active", btn.dataset.view === view);
    });
  }

  updateDashboardChart(view = this.currentView, transactions = this.transactions) {
    // ✅ Always filter first
    const txs = this.filterVisibleTransactions(transactions);

    let grouped = {};

    if (view === "daily") {
      txs.forEach((tx) => {
        grouped[tx.date] = (grouped[tx.date] || 0) + (tx.amount || 0);
      });
    } else if (view === "monthly") {
      txs.forEach((tx) => {
        const month = tx.date.substring(0, 7); // YYYY-MM
        grouped[month] = (grouped[month] || 0) + (tx.amount || 0);
      });
    } else if (view === "yearly") {
      txs.forEach((tx) => {
        const year = tx.date.substring(0, 4); // YYYY
        grouped[year] = (grouped[year] || 0) + (tx.amount || 0);
      });
    }

    const labels = Object.keys(grouped).sort();
    const values = labels.map((l) => grouped[l]);

    const canvas = document.getElementById("revenue-chart");
    if (!canvas) return; // Prevent error if element not found
    const ctx = canvas.getContext("2d");

    if (this.charts.main) this.charts.main.destroy();

    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, "rgba(33,128,141,0.8)");
    gradient.addColorStop(1, "rgba(33,128,141,0.05)");

    this.charts.main = new Chart(ctx, {
      type: "line",
      data: {
        labels,
        datasets: [
          {
            label: "Revenue (₹)",
            data: values,
            borderColor: "#21808D",
            backgroundColor: gradient,
            fill: true,
            tension: 0.3,
            borderWidth: 2,
            pointRadius: 5,
            pointBackgroundColor: "#21808D",
            pointBorderWidth: 2,
          },
        ],
      },
      options: {
        responsive: true,
        plugins: {
          legend: { display: false },
          tooltip: { mode: "index", intersect: false },
        },
        scales: {
          x: { grid: { display: false } },
          y: { beginAtZero: true },
        },
      },
    });

    // ✅ Blur logic (same as widgets & revenue split)
    const role = (this.currentUser?.role || "").toLowerCase();
    const canViewRevenue = role === "admin" || role === "super admin";
    canvas.classList.toggle("blurred", !canViewRevenue);

    if (!canViewRevenue) {
      canvas.title = "Revenue hidden for staff users";
    } else {
      canvas.removeAttribute("title");
    }
  }

  updatePeakHourChart(view = this.currentView, transactions = this.transactions) {
    const today = new Date();
    const todayStr = today.toISOString().split("T")[0]; // e.g. "2025-09-08"

    // ✅ apply role-based filter first
    const txs = this.filterVisibleTransactions(transactions);

    // ✅ apply view-based filtering on already filtered txs
    const filteredTx = txs.filter((tx) => {
      const txDateStr = tx.date; // already "YYYY-MM-DD"

      if (view === "daily") {
        return txDateStr === todayStr;
      } else if (view === "monthly") {
        return txDateStr.substring(0, 7) === todayStr.substring(0, 7); // "YYYY-MM"
      } else if (view === "yearly") {
        return txDateStr.substring(0, 4) === todayStr.substring(0, 4); // "YYYY"
      }
      return true;
    });

    // Initialize 24-hour slots
    let hourlyCount = Array(24).fill(0);

    // Count sessions per hour
    filteredTx.forEach((tx) => {
      if (!tx.time) return; // safeguard
      const hour = parseInt(tx.time.split(":")[0], 10);
      if (!isNaN(hour)) hourlyCount[hour] += 1;
    });

    // Hour labels in 12-hour format
    const labels = Array.from({ length: 24 }, (_, h) => {
      const suffix = h < 12 ? "AM" : "PM";
      const hour12 = h % 12 || 12;
      return `${hour12} ${suffix}`;
    });

    // Highlight busiest hour
    const maxSessions = Math.max(...hourlyCount);
    const colors = hourlyCount.map((val) => (val === maxSessions ? "#F39C12" : "#21808D"));

    // Render chart
    const canvas = document.getElementById("peak-hour-chart");
    if (!canvas) return;
    const ctx = canvas.getContext("2d");

    if (this.charts.peakHour) this.charts.peakHour.destroy();

    this.charts.peakHour = new Chart(ctx, {
      type: "bar",
      data: {
        labels,
        datasets: [
          {
            label: "Sessions",
            data: hourlyCount,
            backgroundColor: colors,
          },
        ],
      },
      options: {
        responsive: true,
        plugins: {
          legend: { display: false },
          tooltip: {
            callbacks: {
              label: (ctx) => `${ctx.raw} session${ctx.raw !== 1 ? "s" : ""}`,
            },
          },
        },
        scales: {
          x: { title: { display: true, text: "Hour of Day" }, grid: { display: false } },
          y: { beginAtZero: true, title: { display: true, text: "Sessions" } },
        },
      },
    });
  }

  updateCustomerTrendChart(view = this.currentView, transactions = this.transactions) {
    // ✅ filter by role first
    const txs = this.filterVisibleTransactions(transactions);

    let grouped = {};

    txs.forEach((tx) => {
      let key;
      if (view === "daily") {
        key = tx.date; // full date (YYYY-MM-DD)
      } else if (view === "monthly") {
        key = tx.date.substring(0, 7); // YYYY-MM
      } else {
        key = tx.date.substring(0, 4); // YYYY
      }
      grouped[key] = (grouped[key] || 0) + 1;
    });

    // Sort keys
    const sortedKeys = Object.keys(grouped).sort();

    // Format labels nicely
    const labels = sortedKeys.map((l) => {
      if (view === "daily") {
        return new Date(l).toLocaleDateString("en-US", { day: "numeric", month: "short" }); // e.g., 7 Sep
      } else if (view === "monthly") {
        return new Date(l + "-01").toLocaleDateString("en-US", { month: "short", year: "numeric" }); // Sep 2025
      } else {
        return l; // year
      }
    });

    const values = sortedKeys.map((l) => grouped[l]);

    const canvas = document.getElementById("customer-trend-chart");
    if (!canvas) return;
    const ctx = canvas.getContext("2d");

    if (this.charts.customerTrend) this.charts.customerTrend.destroy();

    this.charts.customerTrend = new Chart(ctx, {
      type: "bar",
      data: {
        labels,
        datasets: [
          {
            label: "Customers",
            data: values,
            backgroundColor: "#21808D",
          },
        ],
      },
      options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
          x: {
            title: {
              display: true,
              text: view === "daily" ? "Day" : view === "monthly" ? "Month" : "Year",
            },
          },
          y: { beginAtZero: true, title: { display: true, text: "Customers" } },
        },
      },
    });
  }

  updateConsoleUtilizationChart(view = this.currentView, transactions = this.transactions) {
    const today = new Date();
    const todayStr = today.toISOString().split("T")[0]; // YYYY-MM-DD

    // ✅ Apply role-based filter first
    const txs = this.filterVisibleTransactions(transactions);

    // ✅ Then filter by current view (daily/monthly/yearly)
    const filteredTx = txs.filter((tx) => {
      const txDateStr = tx.date; // "YYYY-MM-DD"
      if (view === "daily") {
        return txDateStr === todayStr;
      } else if (view === "monthly") {
        return txDateStr.substring(0, 7) === todayStr.substring(0, 7); // "YYYY-MM"
      } else if (view === "yearly") {
        return txDateStr.substring(0, 4) === todayStr.substring(0, 4); // "YYYY"
      }
      return true;
    });

    // ✅ Aggregate hours played per console
    const consoleUsage = {};
    filteredTx.forEach((tx) => {
      if (!tx.duration) return; // skip if duration missing
      const [hh, mm, ss] = tx.duration.split(":").map(Number);
      const hours = hh + mm / 60 + ss / 3600;
      consoleUsage[tx.console] = (consoleUsage[tx.console] || 0) + hours;
    });

    const labels = Object.keys(consoleUsage);
    const values = Object.values(consoleUsage);

    const canvas = document.getElementById("console-utilization-chart");
    if (!canvas) return;
    const ctx = canvas.getContext("2d");

    if (this.charts.consoleUtilization) this.charts.consoleUtilization.destroy();

    this.charts.consoleUtilization = new Chart(ctx, {
      type: "bar",
      data: {
        labels,
        datasets: [
          {
            label: "Hours Played",
            data: values,
            backgroundColor: "#21808D",
          },
        ],
      },
      options: {
        indexAxis: "y", // horizontal bar
        responsive: true,
        plugins: {
          legend: { display: false },
          tooltip: {
            callbacks: {
              label: (ctx) => `${ctx.raw.toFixed(2)} hours`,
            },
          },
        },
        scales: {
          x: { beginAtZero: true, title: { display: true, text: "Hours" } },
          y: { title: { display: true, text: "Consoles" } },
        },
      },
    });
  }
  updateRevenueSplitChart(view, txs) {
    let gaming = 0,
      food = 0;
    const today = new Date().toISOString().split("T")[0];
    const currentMonth = today.substring(0, 7);
    const currentYear = today.substring(0, 4);

    txs.forEach((tx) => {
      if (view === "daily" && tx.date === today) {
        gaming += tx.gamingAmount || 0;
        food += tx.foodAmount || 0;
      } else if (view === "monthly" && tx.date.startsWith(currentMonth)) {
        gaming += tx.gamingAmount || 0;
        food += tx.foodAmount || 0;
      } else if (view === "yearly" && tx.date.startsWith(currentYear)) {
        gaming += tx.gamingAmount || 0;
        food += tx.foodAmount || 0;
      }
    });

    const canvas = document.getElementById("revenue-split-chart");
    if (!canvas) return;
    const ctx = canvas.getContext("2d");

    if (this.charts.revenueSplit) this.charts.revenueSplit.destroy();

    this.charts.revenueSplit = new Chart(ctx, {
      type: "doughnut",
      data: {
        labels: ["Gaming", "Food/Drinks"],
        datasets: [
          {
            data: [gaming, food],
            backgroundColor: ["#21808D", "#F39C12"],
          },
        ],
      },
      options: {
        responsive: true,
        plugins: {
          legend: { position: "bottom" },
          tooltip: {
            callbacks: {
              label: (c) => `${c.label}: $${c.raw.toFixed(2)}`,
            },
          },
        },
      },
    });

    // ✅ Blur logic
    const role = (this.currentUser?.role || "").toLowerCase();
    const canViewRevenue = role === "admin" || role === "super admin";
    canvas.classList.toggle("blurred", !canViewRevenue);
    if (!canViewRevenue) {
      canvas.title = "Revenue hidden for staff users";
    } else {
      canvas.removeAttribute("title");
    }
  }

  getTodayDate() {
    return new Date().toISOString().split("T")[0]; // YYYY-MM-DD
  }
  updateWidgetsByView(view = this.currentView, transactions = this.transactions) {
    const now = new Date();

    // ✅ apply role-based filter first
    const txs = this.filterVisibleTransactions(transactions);

    // ✅ then apply view filter
    let filteredTx = txs;
    if (view === "daily") {
      const today = now.toISOString().split("T")[0];
      filteredTx = txs.filter((tx) => tx.date === today);
    } else if (view === "monthly") {
      const ym = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, "0")}`;
      filteredTx = txs.filter((tx) => tx.date.startsWith(ym));
    } else if (view === "yearly") {
      const year = `${now.getFullYear()}`;
      filteredTx = txs.filter((tx) => tx.date.startsWith(year));
    }

    // ✅ System total time
    this.calculateSystemTotalTime(view, filteredTx);

    // ✅ Revenue
    const totalRevenue = filteredTx.reduce((sum, tx) => sum + (tx.amount || 0), 0);
    const revenueEl = document.getElementById("today-revenue");
    if (revenueEl) {
      revenueEl.textContent = `₹${totalRevenue.toFixed(2)}`;

      const role = (this.currentUser?.role || "").toLowerCase();
      const canViewRevenue = role === "admin" || role === "super admin";

      revenueEl.classList.toggle("blurred", !canViewRevenue);
      revenueEl.style.filter = canViewRevenue ? "" : "blur(7px)";
      if (!canViewRevenue) {
        revenueEl.title = "Hidden for staff users";
      } else {
        revenueEl.removeAttribute("title");
      }
    }

    // ✅ Customer count (unique customers)
    const customerCount = new Set(filteredTx.map((tx) => tx.customerName)).size;
    this.analytics.todayCustomers = customerCount;

    const customerEl = document.getElementById("today-customers");
    if (customerEl) customerEl.textContent = customerCount;

    // ✅ Peak Hours
    this.updatePeakHours(filteredTx);

    // ✅ Avg Session Duration
    this.updateAverageSessionDuration(filteredTx);

    // ✅ Utilization %
    this.updateUtilization(filteredTx);
  }

  resetStats() {
    if (confirm("Are you sure you want to reset all statistics? This action cannot be undone.")) {
      this.analytics.todayRevenue = 0;
      this.analytics.todayCustomers = 0;
      this.transactions = [];

      this.renderDashboard();
      this.showToast("Statistics reset successfully", "success");
    }
  }

  renderDashboard() {
    // ✅ Always force reset to daily unless explicitly changed
    if (!this.currentView || !["daily", "monthly", "yearly"].includes(this.currentView)) {
      this.currentView = "daily";
    }

    // ✅ Apply role-based filter once
    const txs = this.filterVisibleTransactions(this.transactions);

    // ✅ Calculate uptime + total time first (pass filtered txs)
    this.calculateSystemUptime();
    this.calculateSystemTotalTime(this.currentView, txs);

    // ✅ Always use currentView
    this.updateWidgetsByView(this.currentView, txs);

    // ✅ Active consoles
    const activeCount = this.consoles.filter((c) => c.currentSession).length;
    document.getElementById("active-consoles").textContent = `${activeCount}/${this.consoles.length}`;

    // ✅ Update charts with filtered txs
    this.updateDashboardChart(this.currentView, txs);
    this.updatePeakHourChart(this.currentView, txs);
    this.updateCustomerTrendChart(this.currentView, txs);
    this.updateRevenueSplitChart(this.currentView, txs);
    this.updateConsoleUtilizationChart(this.currentView, txs);
  }

  filterVisibleTransactions(transactions) {
    const isSuperAdmin = (this.currentUser?.role || "").toLowerCase() === "super admin";

    return transactions.filter((tx) => {
      // Hide Super Admin–created ones from Admin/Staff
      if (tx.createdBySuperAdmin) {
        return isSuperAdmin;
      }
      return true;
    });
  }

  renderConsoles() {
    const grid = document.getElementById("console-grid");
    if (!grid) return;

    grid.innerHTML = "";

    const canEdit = this.hasFullAccess(); // ✅ Only Admin, Manager, Super Admin

    this.consoles.forEach((console) => {
      const card = document.createElement("div");
      card.className = `console-card ${console.status}`;

      // ✅ If console has an active session, mark card as active (red) or paused (yellow)
      if (console.currentSession) {
        if (console.currentSession.isPaused) {
          card.classList.add("console-paused");
        } else {
          card.classList.add("console-active");
        }
      }

      let sessionContent = "";
      let actionButtons = "";

      // ✅ Status label handling (Available / Occupied / Paused)
      let statusClass = console.status;
      let statusLabel = console.status.replace("-", " ");
      if (console.currentSession) {
        if (console.currentSession.isPaused) {
          statusClass = "paused";
          statusLabel = "paused";
        } else {
          statusClass = "occupied";
          statusLabel = "occupied";
        }
      }

      if (console.currentSession) {
        const session = console.currentSession;
        const currentTime = this.getCurrentTimeInSeconds(session.startTime);

        let billingHistoryContent = "";
        if (session.billingSegments && session.billingSegments.length > 0) {
          billingHistoryContent = `
                    <div class="session-billing-history">
                        <h5>Previous Segments</h5>
                        ${session.billingSegments
                          .map(
                            (segment) => `
                            <div class="billing-segment-item">
                                <span class="segment-info">${segment.players} players (${this.formatTime(segment.duration * 60)}) - ${console.isPlusAccount ? "VIP" : "Regular"}</span>
                                <span class="segment-amount">$${segment.amount.toFixed(2)}</span>
                            </div>
                        `
                          )
                          .join("")}
                        <div class="total-accumulated">
                            Total so far: $${(session.totalSegmentAmount || 0).toFixed(2)}
                        </div>
                    </div>
                `;
        }

        // ✅ Items with remove buttons
        let itemsContent = "";
        if (session.items && session.items.length > 0) {
          itemsContent = `
                    <div class="session-items">
                        <p><strong>Items:</strong></p>
                        ${session.items
                          .map(
                            (item, index) => `
                            <div class="session-item">
                                ${item.name} (${item.quantity})
                                <button class="remove-item-btn" 
                                        data-console-id="${console.id}" 
                                        data-item-index="${index}">❌</button>
                            </div>
                        `
                          )
                          .join("")}
                    </div>
                `;
        }

        sessionContent = `
                <div class="session-info">
                    <div class="session-timer" id="timer-${console.id}">${this.formatTime(currentTime)}</div>
                    <div class="session-details">
                        <p><strong>Customer:</strong> ${session.customer}</p>
                        <p><strong>Current Players:</strong> ${session.currentPlayers}</p>
                        <p><strong>Rate Type:</strong> ${session.rateType === "vip" ? "VIP" : "Regular"}</p>
                        <p><strong>Started:</strong> ${this.formatTimeOnly(session.startTime)}</p>
                        ${itemsContent}
                    </div>
                    ${billingHistoryContent}
                </div>
            `;

        actionButtons = `
                <button class="btn btn--sm btn--secondary add-food-btn" data-console-id="${console.id}">Add F&D</button>
                <button class="btn btn--sm btn--secondary transfer-session-btn" data-console-id="${console.id}">Transfer</button>
                <button class="btn btn--sm btn--secondary change-players-btn" data-console-id="${console.id}">Change Players</button>
                <button class="btn btn--sm btn--secondary pause-session-btn" data-console-id="${console.id}">
                    ${console.currentSession.isPaused ? "▶ Resume" : "⏸ Pause"}
                </button>
                <button class="btn btn--warning end-session-btn" data-console-id="${console.id}">End Session</button>
            `;
      } else {
        actionButtons = console.status === "available" ? `<button class="btn btn--primary start-session-btn" data-console-id="${console.id}">Start Session</button>` : `<button class="btn btn--outline" disabled>${console.status.replace("-", " ").toUpperCase()}</button>`;
      }

      card.innerHTML = `
            <div class="console-controls">
                <button class="btn btn--sm btn--outline edit-console-btn" 
                        data-console-id="${console.id}" 
                        ${canEdit ? "" : "disabled"}>✏️</button>
                <button class="btn btn--sm btn--outline delete-console-btn" 
                        data-console-id="${console.id}" 
                        ${canEdit ? "" : "disabled"}>🗑️</button>
            </div>
            <div class="console-header">
                <h3 class="console-name">${console.name}</h3>
                <span class="console-status ${statusClass}">${statusLabel}</span>
            </div>
            <div class="console-details">
                <p class="console-specs">${console.specs}</p>
                <div class="console-meta">
                    <small>${console.type} • ${console.primaryUser} • ${console.location} • ${console.isPlusAccount ? "Plus Membership" : "No Membership"}</small>
                </div>
            </div>
            ${sessionContent}
            <div class="console-actions">
                ${actionButtons}
            </div>
        `;
      grid.appendChild(card);
    });
  }

  pauseSession(consoleId) {
    const console = this.consoles.find((c) => c.id === consoleId);
    if (!console || !console.currentSession) return;

    const session = console.currentSession;

    // Initialize pause history if not already
    if (!session.pauseHistory) session.pauseHistory = [];

    // If currently NOT paused → show modal to pause
    if (!session.isPaused) {
      const modal = document.getElementById("pause-reason-modal");
      const input = document.getElementById("pause-reason-input");
      const cancelBtn = document.getElementById("cancel-pause");
      const confirmBtn = document.getElementById("confirm-pause");

      input.value = "";
      modal.classList.remove("hidden");

      // Cancel button
      const cancelHandler = () => {
        modal.classList.add("hidden");
        cancelBtn.removeEventListener("click", cancelHandler);
        confirmBtn.removeEventListener("click", confirmHandler);
      };

      // Confirm button
      const confirmHandler = () => {
        const reason = input.value.trim();
        if (!reason) {
          this.showToast("Please enter a reason to pause the session.", "warning");
          return;
        }

        session.isPaused = true;
        session.pauseStart = Date.now();
        session.pauseReason = reason;

        this.showToast(`⏸️ Session paused for ${console.name}`, "info");

        const userName = this.currentUser?.fullName || "System";
        this.logActivity(`⏸️ ${userName} paused session on ${console.name} — Reason: ${reason}`);

        modal.classList.add("hidden");
        cancelBtn.removeEventListener("click", cancelHandler);
        confirmBtn.removeEventListener("click", confirmHandler);

        this.renderConsoles();
      };

      cancelBtn.addEventListener("click", cancelHandler);
      confirmBtn.addEventListener("click", confirmHandler);
    } else {
      // RESUME SESSION
      const pauseDuration = Date.now() - session.pauseStart;

      // Add entry to pauseHistory
      session.pauseHistory.push({
        pauseStart: session.pauseStart, // raw timestamp
        resumeTime: Date.now(),
        reason: session.pauseReason || "No reason provided",
      });

      // Resume timer
      session.startTime += pauseDuration; // shift startTime forward
      session.isPaused = false;
      session.pauseStart = null;
      session.pauseReason = null;

      this.showToast(`▶️ Session resumed for ${console.name}`, "success");

      const userName = this.currentUser?.fullName || "System";
      this.logActivity(`▶️ ${userName} resumed session on ${console.name}`);

      this.renderConsoles();
    }
  }

  removeItemFromSession(consoleId, itemIndex) {
    const targetConsole = this.consoles.find((c) => c.id === consoleId);
    if (!targetConsole || !targetConsole.currentSession) return;

    const items = targetConsole.currentSession.items;
    if (!items[itemIndex]) return;

    const removedItem = items[itemIndex];
    const consoleName = targetConsole.name || `Console #${targetConsole.id}`;
    const itemPrice = removedItem.price || 0; // ✅ fallback if price not set

    if (removedItem.quantity > 1) {
      removedItem.quantity -= 1; // decrement quantity
      this.logActivity(`❌ Removed 1 ${removedItem.name} (₹${itemPrice.toFixed(2)}) from ${consoleName} (Remaining: ${removedItem.quantity})`);
    } else {
      items.splice(itemIndex, 1); // remove completely
      this.logActivity(`❌ Removed ${removedItem.name} (₹${itemPrice.toFixed(2)}) from ${consoleName} (All items removed)`);
    }

    this.renderConsoles();
    this.showToast("Item removed successfully", "success");
  }

  applyRoleRestrictions() {
    const canAdd = this.canAddContent();

    const buttons = ["btn-add-console", "btn-add-game", "btn-add-item", "btn-add-coupon"];

    buttons.forEach((id) => {
      const btn = document.getElementById(id);
      if (btn) {
        btn.disabled = !canAdd;
        btn.classList.toggle("disabled", !canAdd); // optional styling
      }
    });
  }

  // ADD CONSOLE FUNCTIONALITY
  showAddConsoleModal() {
    if (!this.hasFullAccess()) {
      this.showToast("You don’t have permission to add consoles", "error");
      return;
    }
    this.currentConsoleId = null;
    document.getElementById("console-modal-title").textContent = "Add New Console";
    this.clearConsoleForm();
    this.showModal("add-console-modal");
  }

  showEditConsoleModal(consoleId) {
    const console = this.consoles.find((c) => c.id === consoleId);
    if (!console) return;

    // 🚫 Block editing if occupied
    if (console.status === "occupied") {
      this.showToast("Cannot edit an occupied console", "error");
      return;
    }

    this.currentConsoleId = consoleId;
    document.getElementById("console-modal-title").textContent = "Edit Console";
    this.populateConsoleForm(console);
    this.showModal("add-console-modal");
  }

  clearConsoleForm() {
    document.getElementById("console-name").value = "";
    document.getElementById("console-type").value = "";
    document.getElementById("console-specs").value = "";
    document.getElementById("console-year").value = "";
    document.getElementById("console-email").value = "";
    document.getElementById("console-user").value = "";
    document.getElementById("console-location").value = "";
    document.getElementById("console-plus").checked = false;
    document.getElementById("console-maintenance").checked = false;
  }

  populateConsoleForm(console) {
    document.getElementById("console-name").value = console.name;
    document.getElementById("console-type").value = console.type;
    document.getElementById("console-specs").value = console.specs;
    document.getElementById("console-year").value = console.purchaseYear;
    document.getElementById("console-email").value = console.email;
    document.getElementById("console-user").value = console.primaryUser;
    document.getElementById("console-location").value = console.location;
    document.getElementById("console-plus").checked = console.isPlusAccount;
    document.getElementById("console-maintenance").checked = console.isMaintenace;
  }

  updateConsoleSpecs(consoleType) {
    const specsInput = document.getElementById("console-specs");
    if (!specsInput) return;

    const specsMap = {
      PC: "RTX 4060, i5-12600K, 16GB RAM",
      PS5: "PlayStation 5, 825GB SSD",
      Xbox: "Xbox Series X, 1TB SSD",
      "Nintendo Switch": "Nintendo Switch OLED, 64GB",
    };

    specsInput.value = specsMap[consoleType] || "";
  }
  saveConsole() {
    const formData = {
      name: document.getElementById("console-name").value.trim(),
      type: document.getElementById("console-type").value,
      specs: document.getElementById("console-specs").value.trim(),
      purchaseYear: parseInt(document.getElementById("console-year").value),
      email: document.getElementById("console-email").value.trim(),
      primaryUser: document.getElementById("console-user").value.trim(),
      location: document.getElementById("console-location").value,
      isPlusAccount: document.getElementById("console-plus").checked,
      isMaintenace: document.getElementById("console-maintenance").checked,
    };

    if (!formData.name || !formData.type || !formData.email || !formData.primaryUser || !formData.location) {
      this.showToast("Please fill all required fields", "error");
      return;
    }

    if (this.currentConsoleId) {
      // Edit existing console
      const consoleIndex = this.consoles.findIndex((c) => c.id === this.currentConsoleId);
      if (consoleIndex !== -1) {
        const oldConsole = this.consoles[consoleIndex];

        this.consoles[consoleIndex] = {
          ...oldConsole,
          ...formData,
          status: formData.isMaintenace ? "maintenance" : "available",
        };

        this.showToast("Console updated successfully", "success");
        this.logActivity(`🖥️ Console updated: ${formData.name} (${formData.type})`);
      }
    } else {
      // Add new console
      const newConsole = {
        id: Math.max(...this.consoles.map((c) => c.id), 0) + 1,
        ...formData,
        status: formData.isMaintenace ? "maintenance" : "available",
        branch: this.currentUser?.branch || 1,
        currentSession: null,
      };
      this.consoles.push(newConsole);
      this.showToast("Console added successfully", "success");
      this.logActivity(`🆕 New console added: ${formData.name} (${formData.type})`);
    }

    this.hideModal("add-console-modal");
    this.renderConsoles();
  }
  deleteConsole(consoleId) {
    const console = this.consoles.find((c) => c.id === consoleId);
    if (!console) return;

    // 🚫 Block delete if console is occupied or has active session
    if (console.status === "occupied" || console.currentSession) {
      this.showToast("Cannot delete an occupied console", "error");
      return;
    }

    if (confirm(`Delete console "${console.name}"? This action cannot be undone.`)) {
      this.consoles = this.consoles.filter((c) => c.id !== consoleId);
      this.renderConsoles();
      this.showToast("Console deleted successfully", "success");

      // ✅ Log the deletion
      this.logActivity(`❌ Console deleted: ${console.name} (${console.type})`);
    }
  }

  deleteTransaction(id) {
    if (this.currentUser.role !== "Super Admin") {
      this.showToast("You are not authorized to delete transactions", "error");
      return;
    }

    this.transactions = this.transactions.filter((tx) => tx.id !== id);
    this.renderTransactions(this.transactions);
    this.showToast("Transaction deleted successfully", "success");
  }

  viewBill(id) {
    const tx = this.transactions.find((t) => t.id === id);
    if (!tx) return;

    const receiptWindow = window.open("", "_blank");
    if (!receiptWindow) return;

    let segmentsHTML = "";
    if (tx.segments) {
      segmentsHTML = tx.segments.map((seg, idx) => `<p>Segment ${idx + 1}: ${seg.players} players (${this.formatTime(seg.duration)}) - ₹${seg.amount.toFixed(2)}</p>`).join("");
    }

    // 🔹 Pause History
    let pauseHistoryHTML = "";
    if (tx.pauseHistory && tx.pauseHistory.length > 0) {
      pauseHistoryHTML = `
            <h3>Pause History:</h3>
            <ul>
                ${tx.pauseHistory
                  .map(
                    (p, i) => `
                    <li>
                        <strong>${i + 1}.</strong> Reason: ${p.reason}<br>
                        From: ${new Date(p.pauseStart).toLocaleString()}<br>
                        To: ${p.resumeTime ? new Date(p.resumeTime).toLocaleString() : "Still Paused"}
                    </li>
                `
                  )
                  .join("")}
            </ul>
        `;
    }

    receiptWindow.document.write(`
        <html>
        <head><title>Receipt - ${tx.id}</title></head>
        <body style="font-family: Arial, sans-serif; padding: 20px;">
            <h2>GameBot Gaming Cafe </h2>
            <hr>
            <p><strong>Transaction ID:</strong> ${tx.id}</p>
            <p><strong>Customer:</strong> ${tx.customerName}</p>
            <p><strong>Console:</strong> ${tx.console}</p>
            <p><strong>Total Duration:</strong> ${tx.duration}</p>
            <h3>Gaming Segments:</h3>
            ${segmentsHTML}
            ${pauseHistoryHTML} <!-- ✅ Pause history included -->
            <p><strong>Gaming Total:</strong> ₹${tx.gamingAmount.toFixed(2)}</p>
            <p><strong>Food & Drinks:</strong> ₹${tx.foodAmount.toFixed(2)}</p>
            <p><strong>Payment Method:</strong> ${tx.payment}</p>
            <p><strong>Staff:</strong> ${tx.staff}</p>
            <hr>
            <p><strong>Grand Total: ₹${tx.amount.toFixed(2)}</strong></p>
            <p>Thank you for choosing GameBot Gaming Cafe!</p>
        </body>
        </html>
    `);
  }

  // Session Management
  showSessionModal(consoleId) {
    this.currentConsoleId = consoleId;
    const customerNameInput = document.getElementById("customer-name");
    const customerNumberInput = document.getElementById("customer-number");
    const playerCountSelect = document.getElementById("player-count");

    if (customerNameInput) customerNameInput.value = "";
    if (customerNumberInput) customerNumberInput.value = "";
    if (playerCountSelect) playerCountSelect.value = "1";

    // ✅ Reset rate type radios (default to Regular)
    const regularRadio = document.querySelector('input[name="rate-type"][value="regular"]');
    const vipRadio = document.querySelector('input[name="rate-type"][value="vip"]');
    if (regularRadio) regularRadio.checked = true;
    if (vipRadio) vipRadio.checked = false;

    this.showModal("session-modal");
  }
  startSession() {
    const customerName = document.getElementById("customer-name").value.trim();
    const playerCount = parseInt(document.getElementById("player-count").value);

    if (!customerName) {
      this.showToast("Please enter customer name", "error");
      return;
    }

    const targetConsole = this.consoles.find((c) => c.id === this.currentConsoleId);
    if (targetConsole) {
      const rateType = document.querySelector('input[name="rate-type"]:checked')?.value || "regular";

      targetConsole.status = "occupied";
      targetConsole.currentSession = {
        customer: customerName,
        startTime: Date.now(),
        currentPlayers: playerCount,
        rateType: rateType,
        isVip: rateType.toLowerCase() === "vip",
        items: [],
        billingSegments: [],
        totalSegmentAmount: 0,
        lastBilledMinutes: 0,

        // 🔥 Pause support
        isPaused: false,
        pauseStart: null,
        pausedElapsed: 0,
        pauseHistory: [], // ✅ Tracks all pauses with reason, start, and end time
      };

      this.hideModal("session-modal");
      this.renderConsoles();
      this.updateAnalytics();
      this.showToast(`Session started (${rateType.toUpperCase()} Rate)`, "success");

      const consoleName = targetConsole.name || `Console #${targetConsole.id}`;
      this.logActivity(`🎮 Started a session on ${consoleName} for ${customerName} (${rateType.toUpperCase()})`);
    }
  }

  updateConsoleTimers() {
    this.consoles.forEach((console) => {
      if (console.currentSession) {
        const session = console.currentSession;

        // 🚨 If paused → stop timer updates
        if (session.isPaused) return;

        const elapsedMs = Date.now() - session.startTime;
        const elapsedMinutes = Math.ceil(elapsedMs / 60000);

        const playerKey = session.currentPlayers + "players";
        const rates = session.rateType === "vip" ? this.priceManagement.vipRates : this.priceManagement.regularRates;

        let totalAmount = 0;

        // Full hour cycles
        const fullCycles = Math.floor(elapsedMinutes / 60);
        totalAmount += fullCycles * (rates[playerKey]?.["60min"] || 0);

        // Remaining minutes
        const cycleMinutes = elapsedMinutes % 60;
        if (cycleMinutes > 0 && cycleMinutes <= 15) {
          totalAmount += rates[playerKey]?.["15min"] || 0;
        } else if (cycleMinutes > 15 && cycleMinutes <= 30) {
          totalAmount += rates[playerKey]?.["30min"] || 0;
        } else if (cycleMinutes > 30 && cycleMinutes <= 45) {
          totalAmount += rates[playerKey]?.["45min"] || 0;
        } else if (cycleMinutes > 45 && cycleMinutes <= 60) {
          totalAmount += rates[playerKey]?.["60min"] || 0;
        }

        session.totalSegmentAmount = totalAmount;

        // Timer
        const hrs = Math.floor(elapsedMinutes / 60);
        const mins = elapsedMinutes % 60;
        const secs = Math.floor((elapsedMs % 60000) / 1000);

        const timerEl = document.querySelector(`#timer-${console.id}`);
        if (timerEl) {
          timerEl.textContent = `${hrs.toString().padStart(2, "0")}:` + `${mins.toString().padStart(2, "0")}:` + `${secs.toString().padStart(2, "0")}`;
        }

        // Amount
        const amountEl = document.querySelector(`#console-${console.id} .console-amount`);
        if (amountEl) {
          amountEl.textContent = `$${session.totalSegmentAmount.toFixed(2)}`;
        }
      }
    });
  }

  logActivity(message) {
    if (!this.currentUser) return;

    const role = (this.currentUser.role || "").toLowerCase();
    if (role.includes("super admin")) return;

    const time = new Date().toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" });

    // Split emoji from text (first char = emoji, rest = message)
    const icon = message.charAt(0);
    const text = message.slice(2); // remove emoji + space

    const logEntry = {
      user: this.currentUser.fullName,
      icon,
      message: text,
      time,
    };

    if (!this.activityLogs) this.activityLogs = [];
    this.activityLogs.unshift(logEntry);

    const logList = document.getElementById("activity-log");
    if (logList) {
      const li = document.createElement("li");
      li.className = "activity-log-item";
      li.innerHTML = `
          <div class="log-card">
            <div class="log-header">
              <span class="time">[${logEntry.time}]</span>
              <span class="user">${logEntry.user}</span>
            </div>
            <div class="log-body">
              <span class="icon">${logEntry.icon}</span>
              <span class="message">${logEntry.message}</span>
            </div>
          </div>
        `;
      logList.prepend(li);
    }
  }

  renderPriceManagement() {
    console.log("Rendering enhanced price management...");
    this.renderRegularRatesTable();
    this.renderVipRatesTable();
    this.renderPeakHoursDisplay();
    this.renderMultipliersDisplay();

    // ✅ Grey out edit buttons only for Staff
    const buttonsToControl = [".edit-peak-hours-btn", ".edit-peak-multiplier-btn", ".edit-weekend-multiplier-btn"];

    buttonsToControl.forEach((selector) => {
      const btn = document.querySelector(selector);
      if (btn) {
        if (this.hasFullAccess()) {
          // Super Admin / Admin / Manager → enabled
          btn.disabled = false;
          btn.classList.remove("btn--disabled");
        } else {
          // Staff → greyed out
          btn.disabled = true;
          btn.classList.add("btn--disabled");
        }
      }
    });
  }

  renderRegularRatesTable() {
    const regularBody = document.getElementById("regular-pricing-body");
    if (!regularBody) return;

    regularBody.innerHTML = "";

    const canEdit = this.hasFullAccess(); // ✅ Only Admin, Manager, Super Admin

    Object.entries(this.priceManagement.regularRates).forEach(([playerKey, rates]) => {
      const players = playerKey.replace("player", "").replace("s", "");
      const row = document.createElement("tr");
      row.innerHTML = `
            <td>${players} Player${players > 1 ? "s" : ""}</td>
            <td>₹${rates["15min"]}</td>
            <td>₹${rates["30min"]}</td>
            <td>₹${rates["45min"]}</td>
            <td>₹${rates["60min"]}</td>
            <td>
                <button class="btn btn--sm btn--outline edit-regular-rates-btn" 
                    data-player-key="${playerKey}" 
                    ${canEdit ? "" : "disabled"}>
                    ✏️ Edit
                </button>
            </td>
        `;
      regularBody.appendChild(row);
    });
  }

  renderVipRatesTable() {
    const vipBody = document.getElementById("vip-pricing-body");
    if (!vipBody) return;

    vipBody.innerHTML = "";

    const canEdit = this.hasFullAccess(); // ✅ Only Admin, Manager, Super Admin

    Object.entries(this.priceManagement.vipRates).forEach(([playerKey, rates]) => {
      const players = playerKey.replace("player", "").replace("s", "");
      const row = document.createElement("tr");
      row.innerHTML = `
           <td>${players} Player${players > 1 ? "s" : ""}</td>
            <td>₹${rates["15min"]}</td>
            <td>₹${rates["30min"]}</td>
            <td>₹${rates["45min"]}</td>
            <td>₹${rates["60min"]}</td>
            <td>
                <button class="btn btn--sm btn--outline edit-vip-rates-btn" 
                    data-player-key="${playerKey}" 
                    ${canEdit ? "" : "disabled"}>
                    ✏️ Edit
                </button>
            </td>
        `;
      vipBody.appendChild(row);
    });
  }

  renderPeakHoursDisplay() {
    const peakHoursContainer = document.getElementById("current-peak-hours");
    if (!peakHoursContainer) return;

    peakHoursContainer.innerHTML = "";

    if (this.priceManagement.peakHours.length === 0) {
      peakHoursContainer.innerHTML = '<p class="no-peak-hours">No peak hours configured</p>';
    } else {
      this.priceManagement.peakHours.forEach((hour) => {
        const hourTag = document.createElement("span");
        hourTag.className = "peak-hour-tag";
        hourTag.textContent = hour;
        peakHoursContainer.appendChild(hourTag);
      });
    }
    // ✅ Only admins/managers/super admin can see the "Edit" or "Add" button
    const editBtn = document.getElementById("edit-peak-hours-btn");
    if (editBtn) {
      if (!this.hasFullAccess()) {
        editBtn.style.display = "none"; // hide for staff
      } else {
        editBtn.style.display = ""; // show for admin/manager
      }
    }
  }

  renderMultipliersDisplay() {
    const peakMultiplierElement = document.getElementById("peak-multiplier-value");
    const weekendMultiplierElement = document.getElementById("weekend-multiplier-value");

    if (peakMultiplierElement) {
      peakMultiplierElement.textContent = `${this.priceManagement.peakMultiplier}x`;
    }

    if (weekendMultiplierElement) {
      weekendMultiplierElement.textContent = `${this.priceManagement.weekendMultiplier}x`;
    }
    // ✅ Only admins/managers/super admin can see the "Edit" or "Add" button
    const editBtn = document.getElementById("edit-peak-hours-btn");
    if (editBtn) {
      if (!this.hasFullAccess()) {
        editBtn.style.display = "none"; // hide for staff
      } else {
        editBtn.style.display = ""; // show for admin/manager
      }
    }
  }

  // Enhanced Price Management Methods
  showEditRegularRatesModal(playerKey = null) {
    console.log("Showing edit regular rates modal");
    // Pre-fill the form with current regular rates
    Object.entries(this.priceManagement.regularRates).forEach(([key, rates]) => {
      const players = key.replace("player", "").replace("s", "");
      const min15Input = document.getElementById(`regular-${players}p-15min`);
      const min30Input = document.getElementById(`regular-${players}p-30min`);
      const min45Input = document.getElementById(`regular-${players}p-45min`);
      const min60Input = document.getElementById(`regular-${players}p-60min`);

      if (min15Input) min15Input.value = rates["15min"].toFixed(2);
      if (min30Input) min30Input.value = rates["30min"].toFixed(2);
      if (min45Input) min45Input.value = rates["45min"].toFixed(2);
      if (min60Input) min60Input.value = rates["60min"].toFixed(2);
    });

    this.showModal("edit-regular-rates-modal");
  }

  showEditVipRatesModal(playerKey = null) {
    console.log("Showing edit VIP rates modal");
    // Pre-fill the form with current VIP rates
    Object.entries(this.priceManagement.vipRates).forEach(([key, rates]) => {
      const players = key.replace("player", "").replace("s", "");
      const min15Input = document.getElementById(`vip-${players}p-15min`);
      const min30Input = document.getElementById(`vip-${players}p-30min`);
      const min45Input = document.getElementById(`vip-${players}p-45min`);
      const min60Input = document.getElementById(`vip-${players}p-60min`);

      if (min15Input) min15Input.value = rates["15min"].toFixed(2);
      if (min30Input) min30Input.value = rates["30min"].toFixed(2);
      if (min45Input) min45Input.value = rates["45min"].toFixed(2);
      if (min60Input) min60Input.value = rates["60min"].toFixed(2);
    });

    this.showModal("edit-vip-rates-modal");
  }

  showEditPeakHoursModal() {
    console.log("Showing edit peak hours modal");
    // Generate time checkboxes for 24 hours
    const timeCheckboxesContainer = document.querySelector(".time-checkboxes");
    if (!timeCheckboxesContainer) return;

    timeCheckboxesContainer.innerHTML = "";

    for (let hour = 0; hour < 24; hour++) {
      const timeString = `${hour.toString().padStart(2, "0")}:00`;
      const isChecked = this.priceManagement.peakHours.includes(timeString);

      const checkboxDiv = document.createElement("div");
      checkboxDiv.className = `time-checkbox ${isChecked ? "checked" : ""}`;
      checkboxDiv.innerHTML = `
                <input type="checkbox" id="hour-${hour}" value="${timeString}" ${isChecked ? "checked" : ""}>
                <label for="hour-${hour}">${timeString}</label>
            `;

      timeCheckboxesContainer.appendChild(checkboxDiv);
    }

    this.showModal("edit-peak-hours-modal");
  }

  showEditPeakMultiplierModal() {
    console.log("Showing edit peak multiplier modal");
    const peakMultiplierInput = document.getElementById("peak-multiplier-input");
    if (peakMultiplierInput) {
      peakMultiplierInput.value = this.priceManagement.peakMultiplier.toFixed(1);
    }

    this.showModal("edit-peak-multiplier-modal");
  }

  showEditWeekendMultiplierModal() {
    console.log("Showing edit weekend multiplier modal");
    const weekendMultiplierInput = document.getElementById("weekend-multiplier-input");
    if (weekendMultiplierInput) {
      weekendMultiplierInput.value = this.priceManagement.weekendMultiplier.toFixed(1);
    }

    this.showModal("edit-weekend-multiplier-modal");
  }

  saveRegularRates() {
    const rates = {};
    let changes = [];

    for (let players = 1; players <= 4; players++) {
      const playerKey = `${players}player${players > 1 ? "s" : ""}`;
      const min15Input = document.getElementById(`regular-${players}p-15min`);
      const min30Input = document.getElementById(`regular-${players}p-30min`);
      const min45Input = document.getElementById(`regular-${players}p-45min`);
      const min60Input = document.getElementById(`regular-${players}p-60min`);

      if (!min15Input || !min30Input || !min45Input || !min60Input) continue;

      const min15Value = parseFloat(min15Input.value);
      const min30Value = parseFloat(min30Input.value);
      const min45Value = parseFloat(min45Input.value);
      const min60Value = parseFloat(min60Input.value);

      // ✅ Validation
      if (isNaN(min15Value) || min15Value <= 0) {
        this.showToast(`Invalid 15-minute rate for ${players} player(s)`, "error");
        return;
      }
      if (isNaN(min30Value) || min30Value <= 0) {
        this.showToast(`Invalid 30-minute rate for ${players} player(s)`, "error");
        return;
      }
      if (isNaN(min45Value) || min45Value <= 0) {
        this.showToast(`Invalid 45-minute rate for ${players} player(s)`, "error");
        return;
      }
      if (isNaN(min60Value) || min60Value <= 0) {
        this.showToast(`Invalid 60-minute rate for ${players} player(s)`, "error");
        return;
      }

      // ✅ Compare with old values
      const oldRates = this.priceManagement.regularRates[playerKey] || {};
      if (oldRates["15min"] !== undefined && oldRates["15min"] !== min15Value) {
        changes.push(`${players}p 15min: $${oldRates["15min"].toFixed(2)} ➝ $${min15Value.toFixed(2)}`);
      }
      if (oldRates["30min"] !== undefined && oldRates["30min"] !== min30Value) {
        changes.push(`${players}p 30min: $${oldRates["30min"].toFixed(2)} ➝ $${min30Value.toFixed(2)}`);
      }
      if (oldRates["45min"] !== undefined && oldRates["45min"] !== min45Value) {
        changes.push(`${players}p 45min: $${oldRates["45min"].toFixed(2)} ➝ $${min45Value.toFixed(2)}`);
      }
      if (oldRates["60min"] !== undefined && oldRates["60min"] !== min60Value) {
        changes.push(`${players}p 60min: $${oldRates["60min"].toFixed(2)} ➝ $${min60Value.toFixed(2)}`);
      }

      rates[playerKey] = {
        "15min": min15Value,
        "30min": min30Value,
        "45min": min45Value,
        "60min": min60Value,
      };
    }

    // ✅ Update the data
    this.priceManagement.regularRates = rates;

    // ✅ Show toast
    this.showToast("Regular rates updated successfully!", "success");

    // ✅ Log only if there were changes
    if (changes.length > 0) {
      this.logActivity(`💰 Regular rates updated: ${changes.join(", ")}`);
    }

    this.hideModal("edit-regular-rates-modal");
    this.renderPriceManagement();
  }

  saveVipRates() {
    const rates = {};
    let changes = [];

    for (let players = 1; players <= 4; players++) {
      const playerKey = `${players}player${players > 1 ? "s" : ""}`;
      const min15Input = document.getElementById(`vip-${players}p-15min`);
      const min30Input = document.getElementById(`vip-${players}p-30min`);
      const min45Input = document.getElementById(`vip-${players}p-45min`);
      const min60Input = document.getElementById(`vip-${players}p-60min`);

      if (!min15Input || !min30Input || !min45Input || !min60Input) continue;

      const min15Value = parseFloat(min15Input.value);
      const min30Value = parseFloat(min30Input.value);
      const min45Value = parseFloat(min45Input.value);
      const min60Value = parseFloat(min60Input.value);

      // ✅ Validation
      if (isNaN(min15Value) || min15Value <= 0) {
        this.showToast(`Invalid 15-minute VIP rate for ${players} player(s)`, "error");
        return;
      }
      if (isNaN(min30Value) || min30Value <= 0) {
        this.showToast(`Invalid 30-minute VIP rate for ${players} player(s)`, "error");
        return;
      }
      if (isNaN(min45Value) || min45Value <= 0) {
        this.showToast(`Invalid 45-minute VIP rate for ${players} player(s)`, "error");
        return;
      }
      if (isNaN(min60Value) || min60Value <= 0) {
        this.showToast(`Invalid 60-minute VIP rate for ${players} player(s)`, "error");
        return;
      }

      // ✅ Compare with old values
      const oldRates = this.priceManagement.vipRates[playerKey] || {};
      if (oldRates["15min"] !== undefined && oldRates["15min"] !== min15Value) {
        changes.push(`${players}p 15min: $${oldRates["15min"].toFixed(2)} ➝ $${min15Value.toFixed(2)}`);
      }
      if (oldRates["30min"] !== undefined && oldRates["30min"] !== min30Value) {
        changes.push(`${players}p 30min: $${oldRates["30min"].toFixed(2)} ➝ $${min30Value.toFixed(2)}`);
      }
      if (oldRates["45min"] !== undefined && oldRates["45min"] !== min45Value) {
        changes.push(`${players}p 45min: $${oldRates["45min"].toFixed(2)} ➝ $${min45Value.toFixed(2)}`);
      }
      if (oldRates["60min"] !== undefined && oldRates["60min"] !== min60Value) {
        changes.push(`${players}p 60min: $${oldRates["60min"].toFixed(2)} ➝ $${min60Value.toFixed(2)}`);
      }

      rates[playerKey] = {
        "15min": min15Value,
        "30min": min30Value,
        "45min": min45Value,
        "60min": min60Value,
      };
    }

    // ✅ Update the data
    this.priceManagement.vipRates = rates;

    // ✅ Show toast
    this.showToast("VIP rates updated successfully!", "success");

    // ✅ Log only if there were changes
    if (changes.length > 0) {
      this.logActivity(`👑 VIP rates updated: ${changes.join(", ")}`);
    }

    this.hideModal("edit-vip-rates-modal");
    this.renderPriceManagement();
  }

  savePeakHours() {
    const selectedHours = [];
    const checkboxes = document.querySelectorAll('#edit-peak-hours-modal input[type="checkbox"]:checked');

    checkboxes.forEach((checkbox) => {
      selectedHours.push(checkbox.value);
    });

    // ✅ Compare with old hours before updating
    const oldHours = this.priceManagement.peakHours || [];
    const added = selectedHours.filter((h) => !oldHours.includes(h));
    const removed = oldHours.filter((h) => !selectedHours.includes(h));

    // ✅ Update the data
    this.priceManagement.peakHours = selectedHours;

    this.hideModal("edit-peak-hours-modal");
    this.renderPriceManagement();
    this.showToast(`Peak hours updated! ${selectedHours.length} hours selected.`, "success");

    // ✅ Log activity changes
    if (added.length > 0 || removed.length > 0) {
      let changeMsg = [];
      if (added.length > 0) changeMsg.push(`➕ Added: ${added.join(", ")}`);
      if (removed.length > 0) changeMsg.push(`➖ Removed: ${removed.join(", ")}`);
      this.logActivity(`⏰ Peak hours updated. ${changeMsg.join(" | ")}`);
    } else {
      this.logActivity(`⏰ Peak hours updated (no changes)`);
    }
  }
  savePeakMultiplier() {
    const peakMultiplierInput = document.getElementById("peak-multiplier-input");
    if (!peakMultiplierInput) return;

    const multiplier = parseFloat(peakMultiplierInput.value);

    // Validation
    if (isNaN(multiplier) || multiplier < 1.0 || multiplier > 3.0) {
      this.showToast("Peak multiplier must be between 1.0 and 3.0", "error");
      return;
    }

    // ✅ Compare with previous multiplier
    const oldMultiplier = this.priceManagement.peakMultiplier || 1.0;

    // Update the data
    this.priceManagement.peakMultiplier = multiplier;

    this.hideModal("edit-peak-multiplier-modal");
    this.renderPriceManagement();
    this.showToast(`Peak multiplier updated to ${multiplier}x`, "success");

    // ✅ Log activity only if changed
    if (multiplier !== oldMultiplier) {
      this.logActivity(`⚡ Peak multiplier changed from ${oldMultiplier}x ➝ ${multiplier}x`);
    }
  }

  saveWeekendMultiplier() {
    const weekendMultiplierInput = document.getElementById("weekend-multiplier-input");
    if (!weekendMultiplierInput) return;

    const multiplier = parseFloat(weekendMultiplierInput.value);

    // Validation
    if (isNaN(multiplier) || multiplier < 1.0 || multiplier > 3.0) {
      this.showToast("Weekend multiplier must be between 1.0 and 3.0", "error");
      return;
    }

    // ✅ Compare with previous multiplier
    const oldMultiplier = this.priceManagement.weekendMultiplier || 1.0;

    // Update the data
    this.priceManagement.weekendMultiplier = multiplier;

    this.hideModal("edit-weekend-multiplier-modal");
    this.renderPriceManagement();
    this.showToast(`Weekend multiplier updated to ${multiplier}x`, "success");

    // ✅ Log activity only if changed
    if (multiplier !== oldMultiplier) {
      this.logActivity(`🌴 Weekend multiplier changed from ${oldMultiplier}x ➝ ${multiplier}x`);
    }
  }

  // Transfer Session Modal
  showTransferModal(consoleId) {
    this.currentConsoleId = consoleId;
    this.selectedTransferConsoleId = null;

    const availableConsoles = this.consoles.filter((c) => c.id !== consoleId && c.status === "available");

    if (availableConsoles.length === 0) {
      this.showToast("No available consoles for transfer", "warning");
      return;
    }

    const listContainer = document.getElementById("available-consoles-list");
    if (listContainer) {
      listContainer.innerHTML = "";

      availableConsoles.forEach((console) => {
        const item = document.createElement("div");
        item.className = "console-transfer-item";
        item.dataset.consoleId = console.id;

        item.innerHTML = `
                    <div class="console-transfer-name">${console.name}</div>
                    <div class="console-transfer-details">${console.type} • ${console.location} • ${console.isPlusAccount ? "VIP" : "Regular"}</div>
                `;

        listContainer.appendChild(item);
      });
    }

    this.showModal("transfer-session-modal");
  }
  confirmTransfer() {
    if (!this.selectedTransferConsoleId) {
      this.showToast("Please select a console for transfer", "error");
      return;
    }

    const sourceConsole = this.consoles.find((c) => c.id === this.currentConsoleId);
    const targetConsole = this.consoles.find((c) => c.id === this.selectedTransferConsoleId);

    if (!sourceConsole || !targetConsole || !sourceConsole.currentSession) {
      this.showToast("Invalid transfer request", "error");
      return;
    }

    // ✅ Prevent overwriting if target already has a session
    if (targetConsole.currentSession) {
      this.showToast(`${targetConsole.name} is already occupied!`, "error");
      return;
    }

    // Capture session & customer
    const sessionData = { ...sourceConsole.currentSession };
    const customerName = sessionData.customer || "Unknown Customer";

    // Transfer session
    targetConsole.currentSession = sessionData;
    targetConsole.status = "occupied";

    // Clear source console
    sourceConsole.currentSession = null;
    sourceConsole.status = "available";

    // Hide modal & update UI
    this.hideModal("transfer-session-modal");
    this.renderConsoles();

    // ✅ Show toast with customer info
    this.showToast(`🔄 ${customerName}'s session transferred from ${sourceConsole.name} ➝ ${targetConsole.name}`, "success");

    // ✅ Log activity with customer info
    this.logActivity(`🔄 Transferred ${customerName}'s session from ${sourceConsole.name} ➝ ${targetConsole.name}`);
  }

  // Change Players Modal
  showChangePlayersModal(consoleId) {
    const console = this.consoles.find((c) => c.id === consoleId);
    if (!console || !console.currentSession) {
      this.showToast("No active session found", "error");
      return;
    }

    const session = console.currentSession;

    // ✅ Block if session is paused
    if (session.isPaused) {
      this.showToast("⏸️ Cannot change players while session is paused.", "warning");
      return;
    }

    this.currentConsoleId = consoleId;
    const currentTime = this.getCurrentTimeInSeconds(session.startTime);

    // ✅ Extract amount from segmentData
    const segmentData = this.calculateSegmentAmount(console, currentTime);
    const currentSegmentAmount = segmentData.amount;

    // Populate modal with current session data
    document.getElementById("current-players-display").textContent = session.currentPlayers.toString();
    document.getElementById("current-duration-display").textContent = this.formatTime(currentTime);
    document.getElementById("current-segment-amount").textContent = `₹${currentSegmentAmount.toFixed(2)}`;

    // Set new player count options (excluding current)
    const newPlayerCountSelect = document.getElementById("new-player-count");
    if (newPlayerCountSelect) {
      newPlayerCountSelect.innerHTML = "";
      for (let i = 1; i <= 4; i++) {
        if (i !== session.currentPlayers) {
          const option = document.createElement("option");
          option.value = i;
          option.textContent = `${i} Player${i > 1 ? "s" : ""}`;
          newPlayerCountSelect.appendChild(option);
        }
      }
    }

    this.showModal("change-players-modal");
  }

  exportTransactions() {
    if (this.currentUser.role !== "Super Admin") {
      this.showToast("You are not authorized to export transactions", "error");
      return;
    }

    // ✅ Use currently rendered transactions (filtered / searched / all)
    const dataToExport = this.lastRenderedTransactions && this.lastRenderedTransactions.length ? this.lastRenderedTransactions : this.transactions;

    const rows = [["Sl.No", "Customer", "Console", "Duration", "Gaming Amount", "Food Amount", "Total Amount", "Payment", "Date", "User"]];
    dataToExport.forEach((tx, index) => {
      rows.push([index + 1, tx.customerName, tx.console, tx.duration, tx.gamingAmount.toFixed(2), tx.foodAmount.toFixed(2), tx.amount.toFixed(2), tx.payment, tx.date, tx.staff]);
    });

    // ✅ Dynamic filename with date
    const today = new Date().toISOString().split("T")[0];
    const filename = `transactions-${today}.csv`;

    let csv = rows.map((r) => r.join(",")).join("\n");
    let blob = new Blob([csv], { type: "text/csv" });
    let link = document.createElement("a");
    link.href = URL.createObjectURL(blob);
    link.download = filename;
    link.click();

    // ✅ Show success toast with count
    this.showToast(`Exported ${dataToExport.length} transaction${dataToExport.length !== 1 ? "s" : ""} successfully`, "success");
  }
  confirmPlayerChange() {
    const targetConsole = this.consoles.find((c) => c.id === this.currentConsoleId);
    if (!targetConsole || !targetConsole.currentSession) return;

    const newPlayerCount = parseInt(document.getElementById("new-player-count").value);
    const session = targetConsole.currentSession;
    const currentTime = this.getCurrentTimeInSeconds(session.startTime);

    // ✅ Extract object from calculateSegmentAmount
    const segmentData = this.calculateSegmentAmount(targetConsole, currentTime);

    const newSegment = {
      players: session.currentPlayers,
      duration: currentTime,
      amount: segmentData.amount, // ✅ final with multipliers
      baseAmount: segmentData.baseAmount, // ✅ store base price
      multipliers: segmentData.multipliers, // ✅ store applied multipliers
      startTime: session.startTime,
      endTime: Date.now(),
    };

    if (!session.billingSegments) {
      session.billingSegments = [];
    }

    // Add current segment to billing history
    session.billingSegments.push(newSegment);

    // Update total segment amount
    session.totalSegmentAmount = (session.totalSegmentAmount || 0) + segmentData.amount;

    // Reset for new player count
    const oldPlayerCount = session.currentPlayers;
    session.currentPlayers = newPlayerCount;
    session.startTime = Date.now(); // Reset timer

    this.hideModal("change-players-modal");
    this.renderConsoles();

    // ✅ Toast message
    this.showToast(`👥 Player count changed on ${targetConsole.name}: ${oldPlayerCount} ➝ ${newPlayerCount} (previous ${this.formatTime(currentTime)} billed $${segmentData.amount.toFixed(2)})`, "success");

    // ✅ Activity log entry
    const customerName = session.customer || "Unknown Customer";
    this.logActivity(`👥 ${customerName}'s session on ${targetConsole.name}: player count changed from ${oldPlayerCount} ➝ ${newPlayerCount} (previous ${this.formatTime(currentTime)} billed $${segmentData.amount.toFixed(2)})`);
  }
  confirmPaymentAndPrint() {
    const targetConsole = this.consoles.find((c) => c.id === this.currentConsoleId);
    if (!targetConsole || !targetConsole.currentSession) return;

    const session = targetConsole.currentSession;
    const currentTime = this.getCurrentTimeInSeconds(session.startTime);

    // Current segment
    const segmentData = this.calculateSegmentAmount(targetConsole, currentTime);
    const currentSegment = {
      players: session.currentPlayers,
      duration: currentTime,
      amount: segmentData.amount,
      baseAmount: segmentData.baseAmount,
      multipliers: segmentData.multipliers,
    };

    if (!session.billingSegments) session.billingSegments = [];
    session.billingSegments.push(currentSegment);

    // Gaming charges before discount
    let originalGamingAmount = session.billingSegments.reduce((sum, seg) => sum + (seg.amount || 0), 0);

    // Food & drink total
    const foodAmount = session.items.reduce((sum, item) => sum + (item.price || 0), 0);

    // Apply coupon (gaming only)
    let discountAmount = 0;
    let selectedCoupon = null;
    const couponSelect = document.getElementById("coupon-select");
    if (couponSelect && couponSelect.value) {
      selectedCoupon = this.coupons.find((c) => c.id == couponSelect.value);

      if (selectedCoupon) {
        // Minimum order check
        if (!selectedCoupon.minOrderAmount || originalGamingAmount >= selectedCoupon.minOrderAmount) {
          if (selectedCoupon.discountType === "percentage") {
            discountAmount = originalGamingAmount * (selectedCoupon.discountAmount / 100);
          } else if (selectedCoupon.discountType === "flat") {
            discountAmount = Math.min(originalGamingAmount, selectedCoupon.discountAmount);
          } else if (selectedCoupon.discountType === "time_bonus") {
            const { discountedGaming: dg, discountAmount: da } = this.calculateTimeBonusDiscount(targetConsole, selectedCoupon);
            discountAmount = da; // only record discount
          }
        } else {
          this.showToast(`Coupon requires minimum gaming amount ₹${selectedCoupon.minOrderAmount}`, "error");
          return;
        }
      }
    }

    // ✅ Grand total = (gaming - discount) + food
    const grandTotal = originalGamingAmount - discountAmount + foodAmount;

    // Payment method
    const paymentMethodRadio = document.querySelector('input[name="payment-method"]:checked');
    if (!paymentMethodRadio) {
      this.showToast("Please select a payment method", "error");
      return;
    }
    const paymentMethod = paymentMethodRadio.value;

    // Split payment
    let paymentBreakdown = null;
    if (paymentMethod === "Cash + UPI") {
      const cashAmount = parseFloat(document.getElementById("cash-amount").value) || 0;
      const upiAmount = parseFloat(document.getElementById("upi-amount").value) || 0;
      if (Math.abs(cashAmount + upiAmount - grandTotal) > 0.01) {
        this.showToast(`Cash + UPI must equal Grand Total (₹${grandTotal.toFixed(2)})`, "error");
        return;
      }
      paymentBreakdown = { cash: cashAmount, upi: upiAmount };
    }
    // Transaction
    const transaction = {
      id: Math.max(...this.transactions.map((t) => t.id), 0) + 1,
      slNo: this.transactions.length + 1,
      customerName: session.customer || "Unknown",
      console: targetConsole.name,
      consoleId: targetConsole.id,
      duration: this.formatTime(session.billingSegments.reduce((sum, seg) => sum + seg.duration, 0)),
      amount: grandTotal,
      gamingAmount: originalGamingAmount,
      foodAmount: foodAmount,
      payment: paymentMethod,
      paymentBreakdown: paymentBreakdown,
      date: new Date().toISOString().split("T")[0],
      time: new Date().toTimeString().split(" ")[0],
      branch: targetConsole.branch,
      staff: this.currentUser?.fullName || "System",
      couponUsed: selectedCoupon ? `${selectedCoupon.name} (${selectedCoupon.code}) - ${selectedCoupon.discountAmount}${selectedCoupon.discountType === "percentage" ? "%" : ""}${selectedCoupon.discountType === "time_bonus" ? " free mins" : ""}` : null,
      discountAmount: discountAmount,
      rateType: session.rateType ? session.rateType.toUpperCase() : "REGULAR",
      segments: [...session.billingSegments],

      // ✅ Add pause history here
      pauseHistory: session.pauseHistory ? [...session.pauseHistory] : [],

      createdBySuperAdmin: this.currentUser?.role?.toLowerCase() === "super admin",
    };

    // Save transaction
    this.transactions.unshift(transaction);
    this.analytics.todayRevenue += grandTotal;
    this.analytics.todayCustomers += 1;

    // Preserve uptime
    if (!targetConsole.totalUptimeMs) targetConsole.totalUptimeMs = 0;
    targetConsole.totalUptimeMs += Date.now() - session.startTime;

    // Reset console
    targetConsole.status = "available";
    targetConsole.currentSession = null;

    // Clear payment fields
    document.getElementById("cash-amount").value = "";
    document.getElementById("upi-amount").value = "";
    this.resetPaymentFields();

    this.hideModal("billing-modal");
    this.renderConsoles();
    this.updateAnalytics();
    this.printReceipt(transaction);

    // Activity log
    const preCouponTotal = originalGamingAmount + foodAmount;
    this.logActivity(`💵 Payment processed: ₹${grandTotal.toFixed(2)} (${paymentMethod}, ${transaction.rateType}, ${session.currentPlayers} player${session.currentPlayers !== 1 ? "s" : ""}, pre-coupon ₹${preCouponTotal.toFixed(2)}), customer: ${transaction.customerName}, console: ${transaction.console}${selectedCoupon ? `, coupon: ${selectedCoupon.name} (${selectedCoupon.discountAmount}${selectedCoupon.discountType === "percentage" ? "%" : ""}${selectedCoupon.discountType === "time_bonus" ? " free mins" : ""} off)` : ""}`);

    this.showToast("Payment confirmed and receipt printed!", "success");
  }

  resetPaymentFields() {
    // Uncheck all payment radios
    document.querySelectorAll('input[name="payment-method"]').forEach((radio) => {
      radio.checked = false;
    });

    // Hide and clear UPI + Cash fields
    const splitDiv = document.getElementById("split-payment-inputs");
    const cashField = document.getElementById("cash-amount");
    const upiField = document.getElementById("upi-amount");

    if (splitDiv) splitDiv.classList.add("hidden");
    if (cashField) cashField.value = "";
    if (upiField) upiField.value = "";
  }

  printReceipt(transaction) {
    const receiptWindow = window.open("", "_blank");
    if (receiptWindow) {
      let segmentsHTML = "";
      if (transaction.segments) {
        segmentsHTML = transaction.segments
          .map((seg, idx) => {
            const base = seg.baseAmount !== undefined ? `₹${seg.baseAmount.toFixed(2)}` : "N/A";

            const multipliers = seg.multipliers && seg.multipliers.length > 0 ? seg.multipliers.join(", ") : "None";

            return `
                    <div>
                        <strong>Segment ${idx + 1}:</strong> ${seg.players} players (${this.formatTime(seg.duration)})<br>
                        Base Rate: ${base}<br>
                        Multipliers: ${multipliers}<br>
                        Final: ₹${seg.amount.toFixed(2)}
                    </div>
                `;
          })
          .join("");
      }

      let pauseHistoryHTML = "";
      if (transaction.pauseHistory && transaction.pauseHistory.length > 0) {
        pauseHistoryHTML = `
                <h4>Pause History:</h4>
                ${transaction.pauseHistory
                  .map(
                    (p, i) => `
                    <div>
                        <strong>${i + 1}.</strong> Reason: ${p.reason}<br>
                        From: ${p.pauseStart ? new Date(p.pauseStart).toLocaleString() : "Invalid Date"}<br>
                        To: ${p.resumeTime ? new Date(p.resumeTime).toLocaleString() : "Still Paused"}
                    </div>
                `
                  )
                  .join("")}
            `;
      }

      let discountHTML = `<p><strong>Coupon Used:</strong> ${transaction.couponUsed || "None"}</p>`;
      if (transaction.discountAmount && transaction.discountAmount > 0) {
        discountHTML += `<p>Discount Applied: -₹${transaction.discountAmount.toFixed(2)}</p>`;
      }

      let paymentHTML = `<p><strong>Payment Method:</strong> ${transaction.payment}</p>`;
      if (transaction.paymentBreakdown) {
        paymentHTML += `
                <p>Cash: ₹${transaction.paymentBreakdown.cash.toFixed(2)}</p>
                <p>UPI: ₹${transaction.paymentBreakdown.upi.toFixed(2)}</p>
            `;
      }

      receiptWindow.document.write(`
            <html>
            <head>
                <title>Receipt - ${transaction.id}</title>
                <style>
                    body {
                        width: 384px; /* 58mm thermal printer width */
                        font-family: monospace;
                        font-size: 12px;
                        line-height: 1.2;
                        margin: 0;
                        padding: 5px;
                    }
                    h4 {
                        margin-bottom: 2px;
                        margin-top: 10px;
                    }
                    hr {
                        border: 1px dashed #000;
                    }
                    div {
                        margin-bottom: 5px;
                    }
                </style>
            </head>
            <body>
                <h2>GameBot Gaming Cafe </h2>
                <hr>
                <p><strong>Transaction ID:</strong> ${transaction.id}</p>
                <p><strong>Customer:</strong> ${transaction.customerName}</p>
                <p><strong>Console:</strong> ${transaction.console}</p>
                <p><strong>Rate Type:</strong> ${transaction.rateType || "REGULAR"}</p>
                <p><strong>Total Duration:</strong> ${transaction.duration}</p>
                <h4>Gaming Segments:</h4>
                ${segmentsHTML}
                <p><strong>Gaming Total:</strong> ₹${transaction.gamingAmount.toFixed(2)}</p>
                <p><strong>Food & Drinks:</strong> ₹${transaction.foodAmount.toFixed(2)}</p>
                ${pauseHistoryHTML}
                ${paymentHTML}
                <p><strong>Staff:</strong> ${transaction.staff}</p>
                <hr>
                ${discountHTML}
                <p><strong>Grand Total: ₹${transaction.amount.toFixed(2)}</strong></p>
                <p>Thank you for choosing GameBot Gaming Cafe!</p>
                <script>
                    window.print();
                    window.close();
                </script>
            </body>
            </html>
        `);
    }
  }

  // End Session / Billing
  endSession(consoleId) {
    const targetConsole = this.consoles.find((c) => c.id === consoleId);

    if (targetConsole && targetConsole.currentSession) {
      // ❌ Block if session is paused
      if (targetConsole.currentSession.isPaused) {
        this.showToast("⏸️ Cannot end session while it is paused. Please resume first.", "warning");
        return;
      }

      const customerName = targetConsole.currentSession.customer;
      const rateType = targetConsole.currentSession.rateType?.toUpperCase() || "REGULAR";
      const consoleName = targetConsole.name || `Console #${targetConsole.id}`;

      // ✅ Log activity
      this.logActivity(`🛑 Ended session on ${consoleName} for ${customerName} (${rateType})`);

      // ✅ Proceed with billing
      this.showBillingModal(targetConsole);
    }
  }

  showBillingModal(targetConsole) {
    this.currentConsoleId = targetConsole.id;
    const session = targetConsole.currentSession;
    if (!session) return;

    const currentTime = this.getCurrentTimeInSeconds(session.startTime);

    // Current segment calculation
    const segmentData = this.calculateSegmentAmount(targetConsole, currentTime);
    const totalGamingCharges = (session.totalSegmentAmount || 0) + segmentData.amount;
    const itemsCharges = session.items.reduce((sum, item) => sum + (item.price || 0), 0);

    // Populate basic billing info
    document.getElementById("bill-customer").textContent = session.customer;
    document.getElementById("bill-duration").textContent = this.formatTime((session.billingSegments?.reduce((sum, seg) => sum + seg.duration, 0) || 0) + currentTime);

    // --- Gaming Segments ---
    const segmentsList = document.getElementById("gaming-segments-list");
    if (segmentsList) {
      let segmentsHTML = "";

      // Previous segments
      if (session.billingSegments?.length > 0) {
        session.billingSegments.forEach((segment, index) => {
          const multipliersText = segment.multipliers?.length > 0 ? ` [${segment.multipliers.join(", ")}]` : "";
          segmentsHTML += `
                    <div class="segment-item">
                        <span class="segment-info">Segment ${index + 1}: ${segment.players} players (${this.formatTime(segment.duration)})</span>
                        <span class="segment-amount-display">
                            Base: ₹${segment.baseAmount?.toFixed(2) || "0.00"}, 
                            Final: ₹${segment.amount.toFixed(2)} ${multipliersText}
                        </span>
                    </div>
                `;
        });
      }

      // Current segment
      const multipliersText = segmentData.multipliers?.length > 0 ? ` [${segmentData.multipliers.join(", ")}]` : "";
      segmentsHTML += `
            <div class="segment-item">
                <span class="segment-info">Current: ${session.currentPlayers} players (${this.formatTime(currentTime)})</span>
                <span class="segment-amount-display">
                    Base: ₹${segmentData.baseAmount.toFixed(2)}, 
                    Final: ₹${segmentData.amount.toFixed(2)} ${multipliersText}
                </span>
            </div>
        `;
      segmentsList.innerHTML = segmentsHTML;
    }

    // --- Pause History ---
    const pauseHistoryList = document.getElementById("pause-history-list");
    if (pauseHistoryList) {
      let pauseHTML = "";
      if (session.pauseHistory && session.pauseHistory.length > 0) {
        session.pauseHistory.forEach((p, idx) => {
          pauseHTML += `
                    <div class="pause-item">
                        <span><strong>${idx + 1}.</strong> Reason: ${p.reason}</span><br>
                        <span>From: ${new Date(p.pauseStart).toLocaleString()}</span><br>
                        <span>To: ${p.resumeTime ? new Date(p.resumeTime).toLocaleString() : "Still Paused"}</span>
                    </div>
                `;
        });
      } else {
        pauseHTML = "<div>No pauses for this session.</div>";
      }
      pauseHistoryList.innerHTML = pauseHTML;
    }

    // --- Coupons ---
    const couponSelect = document.getElementById("coupon-select");
    if (couponSelect) {
      couponSelect.innerHTML = '<option value="">No coupon</option>';
      const elapsedMinutes = Math.floor(currentTime / 60);

      this.coupons
        .filter((c) => c.isActive)
        .forEach((coupon) => {
          if (coupon.discountType === "time_bonus") {
            const base = Number(coupon.baseMinutes || 0);
            const bonus = Number(coupon.bonusMinutes || coupon.discountAmount || 0);
            if (elapsedMinutes >= base) {
              const opt = document.createElement("option");
              opt.value = coupon.id;
              opt.textContent = `${coupon.name} (${coupon.code}) - Play ${base}m → +${bonus}m free${coupon.loopBonus ? " (Loop)" : ""}`;
              couponSelect.appendChild(opt);
            }
          } else {
            const minOrder = Number(coupon.minOrderAmount || 0);
            if (totalGamingCharges >= minOrder) {
              const opt = document.createElement("option");
              opt.value = coupon.id;
              opt.textContent = coupon.discountType === "percentage" ? `${coupon.name} (${coupon.code}) - ${coupon.discountAmount}% off` : `${coupon.name} (${coupon.code}) - ₹${coupon.discountAmount} off`;
              couponSelect.appendChild(opt);
            }
          }
        });

      couponSelect.onchange = () => this.updateGrandTotalWithCoupon();
    }

    // Reset totals
    this.updateGrandTotalWithCoupon();
    this.resetPaymentFields();

    // --- Super Admin visibility logic ---
    if (this.currentUser?.role?.toLowerCase() === "super admin") {
      this.tempTransactionForSuperAdmin = {
        consoleId: targetConsole.id,
        gamingAmount: totalGamingCharges,
        foodAmount: itemsCharges,
        customerName: session.customer,
        createdBy: "super admin",
        visibleOnlyTo: "super admin",
      };
    } else {
      this.tempTransactionForSuperAdmin = null;
    }

    this.showModal("billing-modal");
  }

  toggleDiscountFields() {
    const discountType = document.getElementById("offer-discount-type")?.value;

    const discountValueGroup = document.getElementById("discount-value-group");
    const timeBonusFields = document.querySelectorAll(".time-bonus-field");
    const minOrderField = document.getElementById("offer-min-order-amount")?.parentElement;

    // Show/hide discount value
    if (discountType === "percentage" || discountType === "flat") {
      if (discountValueGroup) discountValueGroup.classList.remove("hidden");
      timeBonusFields.forEach((f) => f.classList.add("hidden"));

      if (minOrderField) minOrderField.classList.remove("hidden"); // show min order
    } else if (discountType === "time_bonus") {
      if (discountValueGroup) discountValueGroup.classList.add("hidden");
      timeBonusFields.forEach((f) => f.classList.remove("hidden"));

      if (minOrderField) minOrderField.classList.add("hidden"); // hide min order
    }
  }

  getRateForMinutes(playerCount, minutes, isVip = false) {
    const rates = isVip ? this.priceManagement.vipRates : this.priceManagement.regularRates;
    // Match calculateSegmentAmount key naming: "1player" for 1, "2players" for 2+
    const key = playerCount === 1 ? "1player" : `${playerCount}players`;
    const rateTable = rates[key] || {};

    const slabKeys = Object.keys(rateTable);
    if (slabKeys.length === 0) return 0;

    // parse slabs as numbers (e.g. "15min" => 15) and sort ascending
    const slabs = slabKeys.map((s) => parseInt(s.replace("min", ""), 10)).sort((a, b) => a - b);
    const maxSlab = slabs[slabs.length - 1];

    let total = 0;
    let remaining = Math.max(0, Math.ceil(minutes)); // ensure integer minutes

    // charge full biggest slabs first (e.g. full hours)
    while (remaining >= maxSlab) {
      total += rateTable[`${maxSlab}min`] || 0;
      remaining -= maxSlab;
    }

    // If any remainder, charge the smallest slab that covers it (ceiling)
    if (remaining > 0) {
      // find first slab >= remainder
      let slabToCharge = slabs.find((s) => s >= remaining);
      if (!slabToCharge) slabToCharge = slabs[slabs.length - 1]; // fallback
      total += rateTable[`${slabToCharge}min`] || 0;
    }

    return Number(total.toFixed(2));
  }

  calculateTimeBonusDiscount(targetConsole, coupon) {
    const session = targetConsole.currentSession;
    if (!session) return { discountedGaming: 0, discountAmount: 0 };

    const currentTime = this.getCurrentTimeInSeconds(session.startTime);
    const sessionMinutes = Math.floor(currentTime / 60);

    const playerCount = session.currentPlayers || 1;
    const isVip = !!session.isVip;

    // Full charge without discount
    const fullCharge = this.getRateForMinutes(playerCount, sessionMinutes, isVip);

    let discountedGaming = fullCharge;
    let discountAmount = 0;

    if (sessionMinutes >= coupon.baseMinutes) {
      let eligibleFree = sessionMinutes - coupon.baseMinutes;

      if (coupon.loopBonus) {
        // e.g. play 240m with base=120, bonus=30 → 2 full cycles = 60 free mins
        const cycles = Math.floor(sessionMinutes / coupon.baseMinutes);
        eligibleFree = Math.min(cycles * coupon.bonusMinutes, sessionMinutes - coupon.baseMinutes);
      } else {
        eligibleFree = Math.min(coupon.bonusMinutes, eligibleFree);
      }

      const chargeableMinutes = Math.max(0, sessionMinutes - eligibleFree);
      discountedGaming = this.getRateForMinutes(playerCount, chargeableMinutes, isVip);
      discountAmount = fullCharge - discountedGaming;
    }

    return { discountedGaming, discountAmount };
  }

  updateGrandTotalWithCoupon() {
    const targetConsole = this.consoles.find((c) => c.id === this.currentConsoleId);
    if (!targetConsole || !targetConsole.currentSession) return;

    const session = targetConsole.currentSession;
    const currentTime = this.getCurrentTimeInSeconds(session.startTime);

    // Original/current gaming charges (previous segments + live segment)
    const segmentData = this.calculateSegmentAmount(targetConsole, currentTime);
    const originalGaming = (session.totalSegmentAmount || 0) + segmentData.amount;

    // Food & drinks (unchanged by coupons)
    const foodAmount = session.items.reduce((sum, item) => sum + (item.price || 0), 0);

    let discountedGaming = originalGaming;
    let discountAmount = 0;
    let discountLabel = "";
    let selectedCoupon = null;

    const couponSelect = document.getElementById("coupon-select");
    const discountEl = document.getElementById("bill-discount");

    if (couponSelect && couponSelect.value) {
      selectedCoupon = this.coupons.find((c) => c.id == couponSelect.value);

      if (selectedCoupon) {
        if (!selectedCoupon.minOrderAmount || originalGaming >= selectedCoupon.minOrderAmount) {
          if (selectedCoupon.discountType === "percentage") {
            discountAmount = originalGaming * (selectedCoupon.discountAmount / 100);
            discountedGaming = Math.max(0, originalGaming - discountAmount);
            discountLabel = `${selectedCoupon.discountAmount}% off`;
          } else if (selectedCoupon.discountType === "flat") {
            discountAmount = Math.min(originalGaming, selectedCoupon.discountAmount);
            discountedGaming = Math.max(0, originalGaming - discountAmount);
            discountLabel = `₹${selectedCoupon.discountAmount} off`;
          } else if (selectedCoupon.discountType === "time_bonus") {
            const { discountedGaming: dg, discountAmount: da } = this.calculateTimeBonusDiscount(targetConsole, selectedCoupon);
            discountedGaming = dg;
            discountAmount = da;
            discountLabel = `Free time (${selectedCoupon.baseMinutes}m → +${selectedCoupon.bonusMinutes}m)`;
          }
        } else {
          this.showToast(`Coupon requires minimum gaming amount ₹${selectedCoupon.minOrderAmount}`, "error");
        }
      }
    }

    // Update display
    document.getElementById("bill-gaming-total").textContent = `₹${originalGaming.toFixed(2)}`;
    document.getElementById("bill-food-total").textContent = `₹${foodAmount.toFixed(2)}`;
    document.getElementById("bill-grand-total").textContent = `₹${(discountedGaming + foodAmount).toFixed(2)}`;

    if (discountEl) {
      if (discountAmount > 0) {
        discountEl.textContent = `- ₹${discountAmount.toFixed(2)} (${discountLabel})`;
      } else {
        discountEl.textContent = selectedCoupon ? `${discountLabel || "No discount"}` : "₹0.00";
      }
    }
  }

  calculateTotalAmount(targetConsole) {
    const session = targetConsole.currentSession;
    if (!session) return 0;

    const currentTime = this.getCurrentTimeInSeconds(session.startTime);

    // Gaming = previous segments + current
    const segmentData = this.calculateSegmentAmount(targetConsole, currentTime);
    const totalGamingCharges = (session.totalSegmentAmount || 0) + segmentData.amount;

    // Items (food & drinks)
    const itemsCharges = session.items.reduce((total, item) => total + (item.price || 0), 0);

    // Apply coupon only to gaming
    let discountedGaming = totalGamingCharges;
    const couponSelect = document.getElementById("coupon-select");
    if (couponSelect && couponSelect.value) {
      const coupon = this.coupons.find((c) => c.id == couponSelect.value);
      if (coupon) {
        if (coupon.discountType === "percentage") {
          discountedGaming = Math.max(0, totalGamingCharges - totalGamingCharges * (coupon.discountAmount / 100));
        } else if (coupon.discountType === "flat") {
          discountedGaming = Math.max(0, totalGamingCharges - coupon.discountAmount);
        } else if (coupon.discountType === "time_bonus") {
          const { discountedGaming: dg } = this.calculateTimeBonusDiscount(targetConsole, coupon);
          discountedGaming = dg;
        }
      }
    }

    const grandTotal = Math.max(0, discountedGaming) + itemsCharges;
    return Number(grandTotal.toFixed(2));
  }

  calculateSegmentAmount(targetConsole, durationSeconds) {
    const session = targetConsole.currentSession || {};
    const players = Math.max(1, parseInt(session.currentPlayers || 1));

    // ✅ Pick correct rate table (Regular / VIP)
    const rateTable = session.rateType === "vip" ? this.priceManagement.vipRates : this.priceManagement.regularRates;

    const playerKey = players === 1 ? "1player" : players + "players";
    const elapsedMinutes = Math.ceil(durationSeconds / 60);

    // Helper function to round up to nearest available pricing tier
    const roundUpToNearestTier = (mins) => {
      // Available tiers: 15, 30, 45, 60 minutes
      const availableTiers = [15, 30, 45, 60];

      // Find the smallest tier that is >= mins
      for (const tier of availableTiers) {
        if (tier >= mins) {
          return tier;
        }
      }

      // If mins exceeds all tiers, return the largest tier
      return 60;
    };

    let baseAmount = 0;

    if (elapsedMinutes <= 60) {
      // For sessions <= 60 minutes, round up to nearest tier
      const billedMinutes = roundUpToNearestTier(elapsedMinutes);

      if (billedMinutes === 15) {
        baseAmount = rateTable[playerKey]?.["15min"] || 0;
      } else if (billedMinutes === 30) {
        baseAmount = rateTable[playerKey]?.["30min"] || 0;
      } else if (billedMinutes === 45) {
        baseAmount = rateTable[playerKey]?.["45min"] || 0;
      } else {
        baseAmount = rateTable[playerKey]?.["60min"] || 0;
      }
    } else {
      // ✅ Beyond 60 minutes (multi-hour support)
      const fullHours = Math.floor(elapsedMinutes / 60);
      const remainder = elapsedMinutes % 60;

      // Full hours charge
      baseAmount = fullHours * (rateTable[playerKey]?.["60min"] || 0);

      // ✅ Round up remainder to nearest tier and charge
      if (remainder > 0) {
        const billedRemainder = roundUpToNearestTier(remainder);

        if (billedRemainder === 15) {
          baseAmount += rateTable[playerKey]?.["15min"] || 0;
        } else if (billedRemainder === 30) {
          baseAmount += rateTable[playerKey]?.["30min"] || 0;
        } else if (billedRemainder === 45) {
          baseAmount += rateTable[playerKey]?.["45min"] || 0;
        } else {
          baseAmount += rateTable[playerKey]?.["60min"] || 0;
        }
      }
    }

    // ======================
    // ✅ Apply multipliers
    // ======================
    let finalAmount = baseAmount;
    let appliedMultipliers = [];

    const start = session.startTime ? new Date(session.startTime) : new Date();

    // Weekend check (Sat = 6, Sun = 0)
    const dow = start.getDay();
    if ((dow === 0 || dow === 6) && this.priceManagement.weekendMultiplier) {
      finalAmount *= this.priceManagement.weekendMultiplier;
      appliedMultipliers.push(`Weekend x${this.priceManagement.weekendMultiplier}`);
    }

    // Peak hour check (support ranges instead of exact HH:MM if configured)
    const startMinutes = start.getHours() * 60 + start.getMinutes();
    (this.priceManagement.peakHours || []).forEach((range) => {
      if (range.start && range.end) {
        const [sh, sm] = range.start.split(":").map(Number);
        const [eh, em] = range.end.split(":").map(Number);
        const startMin = sh * 60 + sm;
        const endMin = eh * 60 + em;

        if (startMinutes >= startMin && startMinutes < endMin) {
          finalAmount *= this.priceManagement.peakMultiplier || 1;
          appliedMultipliers.push(`Peak Hour x${this.priceManagement.peakMultiplier}`);
        }
      }
    });

    return {
      baseAmount: Number(baseAmount.toFixed(2)),
      amount: Number(finalAmount.toFixed(2)),
      multipliers: appliedMultipliers,
    };
  }

  handlePaymentMethodChange(method) {
    const splitPaymentDiv = document.getElementById("split-payment-inputs");
    const cashField = document.getElementById("cash-amount");
    const upiField = document.getElementById("upi-amount");

    if (splitPaymentDiv) {
      if (method === "Cash + UPI") {
        splitPaymentDiv.classList.remove("hidden");
      } else {
        // Hide + clear fields when switching to Cash / Card / UPI
        splitPaymentDiv.classList.add("hidden");
        if (cashField) cashField.value = "";
        if (upiField) upiField.value = "";
      }
    }
  }

  // ===== COMPLETELY FIXED: FOOD AND DRINKS MANAGEMENT =====

  // FIXED: Modal opens properly with enhanced debugging
  showFoodModal(consoleId) {
    console.log("=== OPENING FOOD MODAL ===");
    console.log("Console ID:", consoleId);

    this.currentConsoleId = consoleId;
    this.selectedFoodItems = [];

    // Force render the grid
    this.renderFoodGrid();

    // Force show the modal
    const modal = document.getElementById("food-modal");
    if (modal) {
      modal.classList.remove("hidden");
      modal.style.display = "flex";
      console.log("Food modal shown successfully");

      // Ensure button is clickable after modal opens
      setTimeout(() => {
        const addButton = document.getElementById("add-food-items");
        if (addButton) {
          addButton.disabled = false;
          addButton.style.pointerEvents = "auto";
          addButton.style.cursor = "pointer";
          addButton.textContent = "Select Items to Add";
          console.log("Add button initialized and ready");
        }
      }, 200);
    } else {
      console.error("Food modal not found!");
      this.showToast("Error: Food modal not found", "error");
    }
  }

  // FIXED: Default count = 0 and improved rendering
  renderFoodGrid() {
    const grid = document.getElementById("food-grid");
    if (!grid) return;

    grid.innerHTML = "";

    this.inventory.forEach((item) => {
      if (item.stock > 0) {
        const foodItem = document.createElement("div");
        foodItem.className = "food-item";
        foodItem.dataset.itemId = item.id;
        foodItem.style.border = "2px solid #e0e0e0";
        foodItem.style.padding = "12px";
        foodItem.style.borderRadius = "8px";
        foodItem.style.cursor = "pointer";
        foodItem.style.marginBottom = "10px";

        foodItem.innerHTML = `
                    <div style="font-weight: bold; font-size: 16px; margin-bottom: 8px;">${item.name}</div>
                    <div style="color: #1FB8CD; font-weight: bold; font-size: 18px;">₹${item.sellingPrice.toFixed(2)}</div>
                    <div style="font-size: 12px; color: #666; margin: 4px 0;">Stock: ${item.stock}</div>
                    <div class="quantity-controls" style="display: flex !important; align-items: center; justify-content: center; gap: 10px; margin-top: 10px; padding: 8px; background: #f0f8ff; border-radius: 6px; border: 1px solid #1FB8CD;">
                        <button type="button" class="quantity-minus" style="width: 35px; height: 35px; border: 2px solid #1FB8CD; background: white; color: #1FB8CD; border-radius: 6px; cursor: pointer; font-size: 18px; font-weight: bold; display: flex !important; align-items: center; justify-content: center;">-</button>
                        <span class="quantity-display" style="background: #1FB8CD; color: white; padding: 8px 12px; border-radius: 6px; min-width: 40px; text-align: center; font-weight: bold; font-size: 16px;">0</span>
                        <button type="button" class="quantity-plus" style="width: 35px; height: 35px; border: 2px solid #1FB8CD; background: white; color: #1FB8CD; border-radius: 6px; cursor: pointer; font-size: 18px; font-weight: bold; display: flex !important; align-items: center; justify-content: center;">+</button>
                    </div>
                `;

        grid.appendChild(foodItem);

        // FORCE EVENT LISTENERS - NO CONDITIONS
        const plusBtn = foodItem.querySelector(".quantity-plus");
        const minusBtn = foodItem.querySelector(".quantity-minus");

        plusBtn.onclick = (e) => {
          e.preventDefault();
          e.stopPropagation();
          console.log("PLUS CLICKED for", item.name);
          this.forceChangeQuantity(foodItem, 1);
        };

        minusBtn.onclick = (e) => {
          e.preventDefault();
          e.stopPropagation();
          console.log("MINUS CLICKED for", item.name);
          this.forceChangeQuantity(foodItem, -1);
        };

        // FORCE SELECTION EVENT - Auto-select when quantity > 0
        foodItem.onclick = (e) => {
          if (e.target.classList.contains("quantity-plus") || e.target.classList.contains("quantity-minus")) {
            return;
          }

          console.log("ITEM CLICKED:", item.name);
          const quantityDisplay = foodItem.querySelector(".quantity-display");
          const currentQty = parseInt(quantityDisplay.textContent) || 0;

          if (currentQty > 0) {
            // Auto-select items with quantity > 0
            foodItem.classList.add("selected");
            foodItem.style.border = "2px solid #1FB8CD";
            foodItem.style.background = "#f0fcfe";
          } else {
            // Toggle selection for items with quantity = 0
            foodItem.classList.toggle("selected");

            if (foodItem.classList.contains("selected")) {
              foodItem.style.border = "2px solid #1FB8CD";
              foodItem.style.background = "#f0fcfe";
            } else {
              foodItem.style.border = "2px solid #e0e0e0";
              foodItem.style.background = "white";
            }
          }

          this.updateSelectedFoodItemsArray();
        };
      }
    });

    this.updateAddButtonText();
    console.log("Food grid rendered with default quantity = 0");
  }

  // FIXED: Quantity change with auto-selection logic
  forceChangeQuantity(foodItem, delta) {
    const quantityDisplay = foodItem.querySelector(".quantity-display");
    const stockDiv = foodItem.querySelector('div[style*="font-size: 12px"]'); // matches "Stock: X" div
    const itemId = parseInt(foodItem.dataset.itemId);
    const inventoryItem = this.inventory.find((i) => i.id === itemId);

    if (quantityDisplay && stockDiv && inventoryItem) {
      let currentQuantity = parseInt(quantityDisplay.textContent) || 0;
      let newQuantity = currentQuantity + delta;

      // Keep between 0 and stock
      newQuantity = Math.max(0, Math.min(newQuantity, inventoryItem.stock));

      quantityDisplay.textContent = newQuantity;

      // ✅ Directly update the visible stock number
      const remainingStock = inventoryItem.stock - newQuantity;
      stockDiv.textContent = `Stock: ${remainingStock}`;

      // Highlight when selected
      if (newQuantity > 0) {
        foodItem.classList.add("selected");
        foodItem.style.border = "2px solid #1FB8CD";
        foodItem.style.background = "#f0fcfe";
      } else {
        foodItem.classList.remove("selected");
        foodItem.style.border = "2px solid #e0e0e0";
        foodItem.style.background = "white";
      }

      // Update array
      this.updateSelectedFoodItemsArray();

      // Small bounce animation
      quantityDisplay.style.transform = "scale(1.2)";
      setTimeout(() => {
        quantityDisplay.style.transform = "scale(1)";
      }, 150);
    }
  }

  // ✅ FIXED: Add items function that reduces stock immediately
  addFoodItems() {
    console.log("=== Add food items clicked ===");
    console.log("selectedFoodItems array:", this.selectedFoodItems);

    if (this.selectedFoodItems.length === 0) {
      this.showToast("Please select items with quantity > 0", "error");
      return;
    }

    const targetConsole = this.consoles.find((c) => c.id === this.currentConsoleId);
    if (!targetConsole || !targetConsole.currentSession) {
      this.showToast("No active session found", "error");
      return;
    }

    let addedItems = 0;
    let totalAmount = 0;
    const addedItemNames = [];

    this.selectedFoodItems.forEach((selectedItem) => {
      const inventoryItem = this.inventory.find((i) => i.id === selectedItem.id);

      if (inventoryItem && selectedItem.quantity > 0 && inventoryItem.stock >= selectedItem.quantity) {
        // ✅ Permanent reduction
        inventoryItem.stock -= selectedItem.quantity;

        // Update session
        const existingItem = targetConsole.currentSession.items.find((i) => i.id === selectedItem.id);
        if (existingItem) {
          existingItem.quantity += selectedItem.quantity;
          existingItem.price += selectedItem.totalPrice;
        } else {
          targetConsole.currentSession.items.push({
            id: selectedItem.id,
            name: selectedItem.name,
            quantity: selectedItem.quantity,
            price: selectedItem.totalPrice,
          });
        }

        addedItems += selectedItem.quantity;
        totalAmount += selectedItem.totalPrice;
        addedItemNames.push(`${selectedItem.name} (${selectedItem.quantity})`);

        // ✅ Stock alerts
        if (inventoryItem.stock === 0) {
          this.logActivity(`⛔ ${inventoryItem.name} is now OUT OF STOCK`);
          this.showToast(`⛔ ${inventoryItem.name} is out of stock!`, "error");
        } else if (inventoryItem.stock <= 5) {
          this.logActivity(`⚠️ Low stock alert: Only ${inventoryItem.stock} left for ${inventoryItem.name}`);
          this.showToast(`⚠️ Low stock: ${inventoryItem.name} (${inventoryItem.stock} left)`, "warning");
        }
      } else {
        this.showToast(`Insufficient stock for ${selectedItem.name}`, "warning");
      }
    });

    if (addedItems > 0) {
      // Update UI
      this.renderConsoles();
      this.renderInventory();

      // Reset selection
      this.selectedFoodItems = [];
      this.updateAddButtonText();

      // ✅ Close modal
      this.hideModal("food-modal");

      // ✅ Toast
      this.showToast(`✅ Added ${addedItems} item(s) worth ₹${totalAmount.toFixed(2)}: ${addedItemNames.join(", ")}`, "success");

      // ✅ Log activity for added food
      const consoleName = targetConsole.name || `Console #${targetConsole.id}`;
      this.logActivity(`🍔 Added food items to ${consoleName}: ${addedItemNames.join(", ")} (Total ₹${totalAmount.toFixed(2)})`);
    } else {
      this.showToast("No items were added - check stock levels", "error");
    }
  }

  resetFoodModal() {
    const foodGrid = document.getElementById("food-grid");
    if (!foodGrid) return;

    // Clear selection
    this.selectedFoodItems = [];
    this.updateAddButtonText();

    // Reset every food item card
    foodGrid.querySelectorAll(".food-item").forEach((card) => {
      const itemId = parseInt(card.dataset.itemId);
      const inventoryItem = this.inventory.find((i) => i.id === itemId);

      // Reset quantity
      const quantityDisplay = card.querySelector(".quantity-display");
      if (quantityDisplay) quantityDisplay.textContent = "0";

      // Reset stock label
      const stockLabel = card.querySelector(".stock-label");
      if (stockLabel && inventoryItem) {
        stockLabel.textContent = `Stock: ${inventoryItem.stock}`;
      }

      // Reset visuals
      card.classList.remove("selected");
      card.style.border = "2px solid #e0e0e0";
      card.style.background = "white";
    });
  }

  updateAddButtonText() {
    const addButton = document.getElementById("add-food-items");
    if (addButton) {
      const itemCount = this.selectedFoodItems.length;
      const totalItems = this.selectedFoodItems.reduce((sum, item) => sum + item.quantity, 0);

      if (itemCount > 0) {
        addButton.textContent = `Add ${totalItems} Item(s) - ₹${this.selectedFoodItems.reduce((sum, item) => sum + item.totalPrice, 0).toFixed(2)}`;
        addButton.style.background = "#1FB8CD";
        addButton.style.opacity = "1";
      } else {
        addButton.textContent = "Select Items to Add";
        addButton.style.background = "#ccc";
        addButton.style.opacity = "0.8";
      }

      // ✅ Always enabled
      addButton.disabled = false;
      addButton.style.cursor = "pointer";
      addButton.style.pointerEvents = "auto";
    }
  }

  renderFoodItems() {
    const foodGrid = document.getElementById("food-grid");
    if (!foodGrid) return;

    foodGrid.innerHTML = "";

    this.inventory.forEach((item) => {
      const card = document.createElement("div");
      card.className = "food-item";
      card.dataset.itemId = item.id;

      const isOutOfStock = item.stock <= 0;

      card.innerHTML = `
            <h4>${item.name}</h4>
            <p>Price: $${item.sellingPrice.toFixed(2)}</p>
            <p class="stock-label">Stock: ${item.stock}</p>
            <div class="quantity-controls">
                <button 
                    class="btn btn--sm decrease-btn" 
                    ${isOutOfStock ? "disabled" : ""}
                >-</button>
                <span class="quantity-display">0</span>
                <button 
                    class="btn btn--sm increase-btn" 
                    ${isOutOfStock ? "disabled" : ""}
                >+</button>
            </div>
        `;

      foodGrid.appendChild(card);
    });

    // ✅ Event delegation (attach once only)
    if (!foodGrid.dataset.listenerAttached) {
      foodGrid.addEventListener("click", (e) => {
        const foodItem = e.target.closest(".food-item");
        if (!foodItem) return;

        if (e.target.classList.contains("increase-btn")) {
          this.changeQuantity(foodItem, +1);
        } else if (e.target.classList.contains("decrease-btn")) {
          this.changeQuantity(foodItem, -1);
        }
      });
      foodGrid.dataset.listenerAttached = "1";
    }

    // ✅ Ensure Add Items button shows correct text on load
    this.updateAddButtonText();
  }

  // LEGACY FUNCTIONS (KEEPING FOR COMPATIBILITY)
  changeQuantity(foodItem, delta) {
    // Redirect to new force function
    this.forceChangeQuantity(foodItem, delta);
  }

  updateSelectedFoodItems() {
    // Redirect to new array function
    this.updateSelectedFoodItemsArray();
  }

  updateSelectedFoodItemsArray() {
    this.selectedFoodItems = [];

    // Look at every food item card in the modal
    const allFoodItems = document.querySelectorAll(".food-item");

    allFoodItems.forEach((card) => {
      const itemId = parseInt(card.dataset.itemId);
      const quantityDisplay = card.querySelector(".quantity-display");
      const quantity = quantityDisplay ? parseInt(quantityDisplay.textContent) : 0;
      const inventoryItem = this.inventory.find((i) => i.id === itemId);

      if (inventoryItem && quantity > 0) {
        this.selectedFoodItems.push({
          id: itemId,
          name: inventoryItem.name,
          quantity: quantity,
          price: inventoryItem.sellingPrice,
          totalPrice: inventoryItem.sellingPrice * quantity,
        });

        // Ensure visual selection
        card.classList.add("selected");
        card.style.border = "2px solid #1FB8CD";
        card.style.background = "#f0fcfe";
      } else {
        // Reset visuals for items with 0 qty
        card.classList.remove("selected");
        card.style.border = "2px solid #e0e0e0";
        card.style.background = "white";
      }
    });

    console.log("Selected food items updated:", this.selectedFoodItems);

    // Refresh the "Add Items" button
    this.updateAddButtonText();
  }

  // ===== END FIXED FOOD AND DRINKS MANAGEMENT =====

  // Remaining render methods
  applyTransactionFilters() {
    const start = document.getElementById("filter-start").value;
    const end = document.getElementById("filter-end").value;
    const consoleId = document.getElementById("filter-console").value;

    let filtered = this.transactions.filter((tx) => {
      let valid = true;
      if (start && tx.date < start) valid = false;
      if (end && tx.date > end) valid = false;
      if (consoleId && tx.consoleId != consoleId) valid = false;
      return valid;
    });

    this.renderTransactions(filtered);
  }

  highlightMatch(text) {
    if (!this.currentSearchQuery) return text;

    // Split search query into words (space separated)
    const terms = this.currentSearchQuery.trim().split(/\s+/).filter(Boolean);
    if (terms.length === 0) return text;

    let result = String(text);
    terms.forEach((term) => {
      const regex = new RegExp(`(${term})`, "gi");
      result = result.replace(regex, '<span class="search-highlight">$1</span>');
    });

    return result;
  }

  renderTransactions(transactions = this.transactions) {
    const page = document.getElementById("transactions");
    const tbody = document.getElementById("transactions-table-body");
    const exportBtn = document.getElementById("export-transactions");

    if (!tbody || !page) return;

    // ✅ Reset date range inputs whenever the page is rendered
    const startDateInput = document.getElementById("filter-start");
    const endDateInput = document.getElementById("filter-end");
    if (startDateInput) startDateInput.value = "";
    if (endDateInput) endDateInput.value = "";

    tbody.innerHTML = "";
    const existingOverlay = page.querySelector(".lock-overlay");
    if (existingOverlay) existingOverlay.remove();
    page.classList.remove("page-locked");

    if (exportBtn) exportBtn.style.display = "none";

    const role = (this.currentUser?.role || "").toLowerCase();
    const isStaff = role === "staff";
    const isSuperAdmin = role === "super admin";

    if (isStaff) {
      page.classList.add("page-locked");
      const overlay = document.createElement("div");
      overlay.className = "lock-overlay";
      overlay.innerHTML = `🔒 Access Restricted`;
      page.appendChild(overlay);
      return;
    }

    // --- Filter transactions ---
    let visibleTransactions = transactions.filter((tx) => {
      // Hide Super Admin–created ones from others
      if (tx.createdBySuperAdmin) {
        return isSuperAdmin;
      }
      return true;
    });

    // --- Include temporary transaction for Super Admin ---
    if (isSuperAdmin && this.tempTransactionForSuperAdmin) {
      visibleTransactions.push({
        ...this.tempTransactionForSuperAdmin,
        id: `temp-${Date.now()}`,
        amount: (this.tempTransactionForSuperAdmin.gamingAmount || 0) + (this.tempTransactionForSuperAdmin.foodAmount || 0),
        payment: "Pending",
        date: new Date().toLocaleString(),
        staff: this.currentUser?.fullName || "Super Admin",
      });
    }

    this.lastRenderedTransactions = visibleTransactions;

    visibleTransactions.forEach((tx, index) => {
      const slNo = index + 1;
      tbody.innerHTML += `
            <tr>
                <td>${this.highlightMatch(slNo)}</td>
                <td>${this.highlightMatch(tx.customerName)}</td>
                <td>${this.highlightMatch(tx.console || "-")}</td>
                <td>${this.highlightMatch(tx.duration || "-")}</td>
                <td>${this.highlightMatch((tx.gamingAmount || 0).toFixed(2))}</td>
                <td>${this.highlightMatch((tx.foodAmount || 0).toFixed(2))}</td>
                <td>${this.highlightMatch((tx.amount || 0).toFixed(2))}</td>
                <td>${this.highlightMatch(tx.payment || "-")}</td>
                <td>${this.highlightMatch(tx.date)}</td>
                <td>${this.highlightMatch(tx.staff || "-")}</td>
                <td>
                    <button class="btn btn--sm btn--outline view-bill-btn" data-id="${tx.id}">View</button>
                    <button class="btn btn--sm btn--secondary print-bill-btn" data-id="${tx.id}">Print</button>
                    ${isSuperAdmin ? `<button class="btn btn--sm btn--danger delete-transaction-btn" data-id="${tx.id}">Delete</button>` : ""}
                </td>
            </tr>`;
    });

    // --- Totals ---
    let gamingTotal = 0;
    let foodTotal = 0;
    let overallTotal = 0;

    visibleTransactions.forEach((tx) => {
      gamingTotal += parseFloat(tx.gamingAmount || 0);
      foodTotal += parseFloat(tx.foodAmount || 0);
      overallTotal += parseFloat(tx.amount || 0);
    });

    document.getElementById("gaming-total").textContent = gamingTotal.toFixed(2);
    document.getElementById("food-total").textContent = foodTotal.toFixed(2);
    document.getElementById("grand-total").textContent = overallTotal.toFixed(2);

    if (exportBtn && isSuperAdmin) exportBtn.style.display = "inline-block";
  }

  printBill(id) {
    const tx = this.transactions.find((t) => t.id === id);
    if (tx) {
      this.printReceipt(tx); // ✅ reuse your existing receipt printer
    }
  }

  // ADD INVENTORY FUNCTIONALITY
  showAddInventoryModal() {
    if (!this.hasFullAccess()) {
      this.showToast("You don’t have permission to add inventory items", "error");
      return;
    }
    this.currentItemId = null;
    document.getElementById("inventory-modal-title").textContent = "Add New Item";
    this.clearInventoryForm();
    this.showModal("add-inventory-modal");
  }

  showEditInventoryModal(itemId) {
    const item = this.inventory.find((i) => i.id === itemId);
    if (!item) return;

    this.currentItemId = itemId;
    document.getElementById("inventory-modal-title").textContent = "Edit Inventory Item";
    this.populateInventoryForm(item);
    this.showModal("add-inventory-modal");
  }

  clearInventoryForm() {
    document.getElementById("inventory-name").value = "";
    document.getElementById("inventory-category").value = "";
    document.getElementById("inventory-cost").value = "";
    document.getElementById("inventory-selling").value = "";
    document.getElementById("inventory-stock").value = "";
    document.getElementById("inventory-expiry").value = "";
    document.getElementById("inventory-supplier").value = "";
  }

  populateInventoryForm(item) {
    document.getElementById("inventory-name").value = item.name;
    document.getElementById("inventory-category").value = item.category;
    document.getElementById("inventory-cost").value = item.costPrice;
    document.getElementById("inventory-selling").value = item.sellingPrice;
    document.getElementById("inventory-stock").value = item.stock;
    document.getElementById("inventory-expiry").value = item.expiryDate;
    document.getElementById("inventory-supplier").value = item.supplier || "";
  }
  saveInventory() {
    const formData = {
      name: document.getElementById("inventory-name").value.trim(),
      category: document.getElementById("inventory-category").value,
      costPrice: parseFloat(document.getElementById("inventory-cost").value),
      sellingPrice: parseFloat(document.getElementById("inventory-selling").value),
      stock: parseInt(document.getElementById("inventory-stock").value),
      expiryDate: document.getElementById("inventory-expiry").value,
      supplier: document.getElementById("inventory-supplier").value.trim(),
    };

    if (!formData.name || !formData.category || !formData.costPrice || !formData.sellingPrice || !formData.stock) {
      this.showToast("Please fill all required fields", "error");
      return;
    }

    if (this.currentItemId) {
      // Edit existing item
      const itemIndex = this.inventory.findIndex((i) => i.id === this.currentItemId);
      if (itemIndex !== -1) {
        const oldItem = { ...this.inventory[itemIndex] }; // store old data for logging
        this.inventory[itemIndex] = {
          ...this.inventory[itemIndex],
          ...formData,
          lowStockAlert: Math.ceil(formData.stock * 0.2),
          sku: this.inventory[itemIndex].sku || this.generateSKU(formData.name),
        };
        this.showToast("Inventory item updated successfully", "success");

        // ✅ Log activity
        this.logActivity(`✏️ Updated inventory item "${formData.name}" (Stock: ${oldItem.stock} → ${formData.stock}, Price: ${oldItem.sellingPrice} → ${formData.sellingPrice})`);
      }
    } else {
      // Add new item
      const newItem = {
        id: Math.max(...this.inventory.map((i) => i.id), 0) + 1,
        ...formData,
        branch: this.currentUser?.branch || 1,
        lowStockAlert: Math.ceil(formData.stock * 0.2),
        sku: this.generateSKU(formData.name),
      };
      this.inventory.push(newItem);
      this.showToast("Inventory item added successfully", "success");

      // ✅ Log activity
      this.logActivity(`📦 Added new inventory item "${formData.name}" (Stock: ${formData.stock}, Price: ${formData.sellingPrice})`);
    }

    this.hideModal("add-inventory-modal");
    this.renderInventory();
  }

  generateSKU(name) {
    const prefix = name.substring(0, 2).toUpperCase();
    const suffix = Math.floor(Math.random() * 1000)
      .toString()
      .padStart(3, "0");
    return prefix + suffix;
  }

  renderInventory() {
    const grid = document.getElementById("inventory-grid");
    if (!grid) return;

    grid.innerHTML = "";

    const canEdit = this.hasFullAccess(); // ✅ Only Admin, Manager, Super Admin

    this.inventory.forEach((item) => {
      const card = document.createElement("div");
      card.className = "inventory-card";

      const isLowStock = item.stock <= (item.lowStockAlert || 10);
      const profitMargin = (((item.sellingPrice - item.costPrice) / item.costPrice) * 100).toFixed(1);

      card.innerHTML = `
            <div class="inventory-actions">
                <button class="btn btn--sm btn--outline edit-inventory-btn" data-item-id="${item.id}" ${canEdit ? "" : "disabled"}>✏️</button>
                <button class="btn btn--sm btn--outline delete-inventory-btn" data-item-id="${item.id}" ${canEdit ? "" : "disabled"}>🗑️</button>
            </div>
            <div class="inventory-header">
                <h3 class="inventory-name">${item.name}</h3>
                <div class="inventory-price">₹${item.sellingPrice.toFixed(2)}</div>
            </div>
            <div class="inventory-category">${item.category}</div>
            <div class="inventory-meta">
                <p><strong>Supplier:</strong> ${item.supplier || "N/A"}</p>
                <p><strong>Expiry:</strong> ${item.expiryDate || "N/A"}</p>
                <p><strong>SKU:</strong> ${item.sku || "N/A"}</p>
            </div>
            <div class="stock-info">
                <span class="stock-level ${isLowStock ? "low-stock" : ""}">Stock: ${item.stock}</span>
                <span class="profit-margin">+${profitMargin}%</span>
            </div>
        `;
      grid.appendChild(card);
    });
  }

  deleteInventoryItem(itemId) {
    const item = this.inventory.find((i) => i.id === itemId);
    if (!item) return;

    if (confirm(`Delete "${item.name}" from inventory? This action cannot be undone.`)) {
      this.inventory = this.inventory.filter((i) => i.id !== itemId);
      this.renderInventory();
      this.showToast("Inventory item deleted successfully", "success");

      // ✅ Log activity
      this.logActivity(`🗑️ Deleted inventory item "${item.name}" (Stock: ${item.stock}, Price: ${item.sellingPrice})`);
    }
  }

  // Utility Methods
  getCurrentTimeInSeconds(startTime, session) {
    if (session?.isPaused && session.pauseStart) {
      return Math.floor((session.pauseStart - startTime) / 1000);
    }
    return Math.floor((Date.now() - startTime) / 1000);
  }

  formatTime(seconds) {
    const hours = Math.floor(seconds / 3600);
    const mins = Math.floor((seconds % 3600) / 60);
    const secs = seconds % 60;
    return `${hours.toString().padStart(2, "0")}:` + `${mins.toString().padStart(2, "0")}:` + `${secs.toString().padStart(2, "0")}`;
  }

  formatTimeOnly(timestamp) {
    const date = new Date(timestamp);
    return date.toLocaleTimeString("en-US", { hour12: false, hour: "2-digit", minute: "2-digit", seconds: "2-digit" });
  }

  updateConsoleTimers() {
    this.consoles.forEach((console) => {
      if (console.currentSession) {
        const session = console.currentSession;
        const elapsed = this.getCurrentTimeInSeconds(session.startTime, session);
        const timerElement = document.getElementById(`timer-${console.id}`);
        if (timerElement) {
          timerElement.textContent = this.formatTime(elapsed);
        }
      }
    });
  }

  updateAnalytics() {
    // ✅ Recalculate uptime + total time during refresh
    this.calculateSystemUptime();
    this.calculateSystemTotalTime(this.currentView);

    this.updateDashboardChart();

    this.analytics.activeConsoles = this.consoles.filter((c) => c.status === "occupied").length;
    if (this.currentSection === "dashboard") {
      const activeConsolesElement = document.getElementById("active-consoles");
      if (activeConsolesElement) {
        activeConsolesElement.textContent = `${this.analytics.activeConsoles}/${this.consoles.length}`;
      }
    }
  }

  showModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
      modal.classList.remove("hidden");
      console.log(`Modal ${modalId} shown`);
    } else {
      console.error(`Modal ${modalId} not found`);
    }
  }

  hideModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
      modal.classList.add("hidden");
      console.log(`Modal ${modalId} hidden`);
    }
  }

  showToast(message, type = "info") {
    console.log(`Toast: ${message} (${type})`);
    const toast = document.createElement("div");
    toast.className = `toast toast--${type} show`;
    toast.textContent = message;

    document.body.appendChild(toast);

    setTimeout(() => {
      toast.classList.remove("show");
      setTimeout(() => {
        if (document.body.contains(toast)) {
          document.body.removeChild(toast);
        }
      }, 300);
    }, 3000);
  }

  // Placeholder methods for remaining functionality
  renderGames() {
    const grid = document.getElementById("games-grid");
    if (!grid) return;

    grid.innerHTML = "";

    const canEdit = this.hasFullAccess(); // ✅ Only Admin, Manager, Super Admin

    this.games.forEach((game) => {
      const card = document.createElement("div");
      card.className = "game-card";

      card.innerHTML = `
            <div class="game-actions">
                <button class="btn btn--sm btn--outline edit-game-btn" data-game-id="${game.id}" ${canEdit ? "" : "disabled"}>✏️</button>
                <button class="btn btn--sm btn--outline delete-game-btn" data-game-id="${game.id}" ${canEdit ? "" : "disabled"}>🗑️</button>
            </div>
            <div class="game-header">
                <h3 class="game-title">${game.name}</h3>
                <div class="game-rating">⭐ ${game.rating}</div>
            </div>
            <div class="game-category">${game.category}</div>
            <div class="game-info">
                <p><strong>Developer:</strong> ${game.developer}</p>
                <p><strong>Release:</strong> ${new Date(game.releaseDate).toLocaleDateString()}</p>
            </div>
            <div class="game-consoles">
                Available on ${game.consoles.length} console${game.consoles.length > 1 ? "s" : ""}
            </div>
        `;

      grid.appendChild(card);
    });
  }
  renderOffers() {
    const grid = document.getElementById("offers-grid");
    if (!grid) return;

    grid.innerHTML = "";

    const canEdit = this.hasFullAccess(); // ✅ Only Admin, Manager, Super Admin

    this.coupons.forEach((offer) => {
      const card = document.createElement("div");
      card.className = `offer-card ${!offer.isActive ? "inactive" : ""}`;

      // ✅ Format discount display
      let discountDisplay = "";
      if (offer.discountType === "percentage") {
        discountDisplay = `${offer.discountAmount}% OFF`;
      } else if (offer.discountType === "flat") {
        discountDisplay = `₹${offer.discountAmount} OFF`;
      } else if (offer.discountType === "time_bonus") {
        const base = offer.baseMinutes ?? 0;
        const bonus = offer.bonusMinutes ?? 0;
        discountDisplay = `Play ${base}m → +${bonus}m free ${offer.loopBonus ? "(Loop)" : ""}`;
      }

      // ✅ Optional: show min order only for non-time_bonus
      const minOrderDisplay = offer.discountType !== "time_bonus" ? `Min Order: ₹${offer.minOrderAmount ?? 0}` : "";

      card.innerHTML = `
      <div class="offer-actions">
          <button class="btn btn--sm btn--outline edit-offer-btn" data-offer-id="${offer.id}" ${canEdit ? "" : "disabled"}>✏️</button>
          <button class="btn btn--sm btn--outline toggle-offer-btn" data-offer-id="${offer.id}" ${canEdit ? "" : "disabled"}>
              ${offer.isActive ? "⏸️" : "▶️"}
          </button>
          <button class="btn btn--sm btn--outline delete-offer-btn" data-offer-id="${offer.id}" ${canEdit ? "" : "disabled"}>🗑️</button>
      </div>
      <h3 class="offer-name">${offer.name}</h3>
      <div class="offer-code">${offer.code}</div>
      <p class="offer-description">${offer.description}</p>
      <div class="offer-details">
          <div class="offer-discount">${discountDisplay}</div>
          ${minOrderDisplay ? `<div class="offer-min-order">${minOrderDisplay}</div>` : ""}
          <div class="offer-usage">${offer.usageCount}/${offer.usageLimit} used</div>
      </div>
    `;

      grid.appendChild(card);
    });
  }

  renderUserManagement() {
    const tbody = document.getElementById("users-table-body");
    if (!tbody) return;

    tbody.innerHTML = "";

    // ✅ Filter out Super Admin users
    const filteredUsers = this.systemUsers.filter((user) => (user.role || "").toLowerCase() !== "super admin");

    let serialNo = 1;

    // ✅ Check logged-in role
    const isLoggedInSuperAdmin = (this.currentUser?.role || "").toLowerCase() === "super admin";

    filteredUsers.forEach((user) => {
      const isAdmin = (user.role || "").toLowerCase() === "admin";

      // ✅ Buttons disabled only if user is admin AND logged-in is NOT super admin
      const disabledAttr = isAdmin && !isLoggedInSuperAdmin ? "disabled" : "";

      const row = document.createElement("tr");
      row.innerHTML = `
            <td>${serialNo}</td>
            <td>${user.fullName}</td>
            <td>${user.phone}</td>
            <td>${user.role}</td>
            <td>${this.branches.find((b) => b.id === user.branch)?.name || "N/A"}</td>
            <td><span class="status status--${user.isActive ? "success" : "error"}">${user.isActive ? "Active" : "Inactive"}</span></td>
            <td>
                <div class="action-buttons">
                    <button class="btn btn--sm btn--outline edit-user-btn" data-user-id="${user.id}" ${disabledAttr}>✏️</button>
                    <button class="btn btn--sm btn--outline toggle-user-btn" data-user-id="${user.id}" ${disabledAttr}>
                        ${user.isActive ? "⏸️" : "▶️"}
                    </button>
                    <button class="btn btn--sm btn--outline delete-user-btn" data-user-id="${user.id}" ${disabledAttr}>🗑️</button>
                </div>
            </td>
        `;
      tbody.appendChild(row);
      serialNo++;
    });
  }

  renderBranchManagement() {
    const grid = document.getElementById("branches-grid");
    if (!grid) return;

    grid.innerHTML = "";

    const canEdit = this.hasFullAccess(); // ✅ Only Admin, Manager, Super Admin

    this.branches.forEach((branch) => {
      const card = document.createElement("div");
      card.className = `branch-card ${!branch.isActive ? "inactive" : ""}`;

      card.innerHTML = `
            <div class="branch-actions">
                <button class="btn btn--sm btn--outline edit-branch-btn" data-branch-id="${branch.id}" ${canEdit ? "" : "disabled"}>✏️</button>
                <button class="btn btn--sm btn--outline delete-branch-btn" data-branch-id="${branch.id}" ${canEdit ? "" : "disabled"}>🗑️</button>
            </div>
            <div class="branch-header">
                <h3 class="branch-name">${branch.name}</h3>
                <span class="branch-status">${branch.isActive ? "Active" : "Inactive"}</span>
            </div>
            <div class="branch-location">${branch.location}</div>
            <div class="branch-details">
                <p><strong>Address:</strong> ${branch.address}</p>
                <p><strong>Contact:</strong> ${branch.contact}</p>
                <p><strong>Manager:</strong> ${branch.manager}</p>
                <p><strong>Timing:</strong> ${branch.timing}</p>
            </div>
            <div class="branch-stats">
                <div class="branch-stat">
                    <div class="branch-stat-value">${branch.console}</div>
                    <div class="branch-stat-label">Consoles</div>
                </div>
                <div class="branch-stat">
                    <div class="branch-stat-value">${new Date(branch.established).getFullYear()}</div>
                    <div class="branch-stat-label">Established</div>
                </div>
            </div>
        `;
      grid.appendChild(card);
    });
  }

  renderProfile() {
    if (this.currentUser) {
      const nameInput = document.getElementById("profile-name");
      const emailInput = document.getElementById("profile-email");

      if (nameInput) nameInput.value = this.currentUser.fullName;
      if (emailInput) emailInput.value = this.currentUser.email;
    }
  }

  // Placeholder methods for additional modals
  showAddGameModal() {
    if (!this.hasFullAccess()) {
      this.showToast("You don’t have permission to add games", "error");
      return;
    }
    this.currentGameId = null;
    const title = document.getElementById("game-modal-title");
    if (title) title.textContent = "Add New Game";
    this.clearGameForm();
    this.renderConsoleAssignment([]);
    this.showModal("add-game-modal");
  }

  clearGameForm() {
    const fields = ["game-name", "game-category", "game-developer", "game-rating", "game-release"];
    fields.forEach((id) => {
      const el = document.getElementById(id);
      if (el) el.value = "";
    });
    const container = document.getElementById("console-assignment");
    if (container) container.innerHTML = "";
  }

  populateGameForm(game) {
    const set = (id, val) => {
      const el = document.getElementById(id);
      if (el) el.value = val ?? "";
    };
    set("game-name", game.name);
    set("game-category", game.category);
    set("game-developer", game.developer);
    set("game-rating", game.rating);
    set("game-release", game.releaseDate ? new Date(game.releaseDate).toISOString().slice(0, 10) : "");
  }
  showAddOfferModal() {
    if (!this.hasFullAccess()) {
      this.showToast("You don’t have permission to create coupons", "error");
      return;
    }
    this.currentOfferId = null;
    const title = document.getElementById("offer-modal-title");
    if (title) title.textContent = "Create Coupon";
    this.clearOfferForm();
    this.showModal("offer-modal");
  }

  clearOfferForm() {
    const map = ["offer-name", "offer-code", "offer-description", "offer-discount-type", "offer-discount-amount", "offer-usage-limit", "offer-valid-from", "offer-valid-to", "offer-min-order-amount", "offer-base-minutes", "offer-bonus-minutes"];

    map.forEach((id) => {
      const el = document.getElementById(id);
      if (el) el.value = ""; // leave discount-type empty so it lands on "-- Select type --"
    });

    const active = document.getElementById("offer-active");
    if (active) active.checked = true;

    const loopBonus = document.getElementById("offer-loop-bonus");
    if (loopBonus) loopBonus.checked = false;
  }

  saveOffer() {
    const get = (id) => document.getElementById(id);
    const name = get("offer-name")?.value?.trim();
    const code = get("offer-code")?.value?.trim();
    const description = get("offer-description")?.value?.trim() || "";
    const discountType = get("offer-discount-type")?.value || "percentage";
    const usageLimit = parseInt(get("offer-usage-limit")?.value || "0");
    let minOrderAmount = parseFloat(get("offer-min-order-amount")?.value || "0");
    const validFrom = get("offer-valid-from")?.value || "";
    const validTo = get("offer-valid-to")?.value || "";
    const isActive = !!get("offer-active")?.checked;

    if (!name || !code) return this.showToast("Name and Code are required", "error");

    // ✅ Handle discount based on type
    let discountAmount = 0;
    let baseMinutes = 0;
    let bonusMinutes = 0;

    if (discountType === "percentage" || discountType === "flat") {
      discountAmount = parseFloat(get("offer-discount-amount")?.value || "0");
    } else if (discountType === "time_bonus") {
      baseMinutes = parseInt(get("offer-base-minutes")?.value || "0");
      bonusMinutes = parseInt(get("offer-bonus-minutes")?.value || "0");
      minOrderAmount = 0; // ✅ Time bonus ignores min order
    }
    const loopBonus = !!get("offer-loop-bonus")?.checked;
    const payload = {
      name,
      code,
      description,
      discountType,
      discountAmount: isNaN(discountAmount) ? 0 : discountAmount,
      baseMinutes: isNaN(baseMinutes) ? 0 : baseMinutes,
      bonusMinutes: isNaN(bonusMinutes) ? 0 : bonusMinutes,
      usageLimit: isNaN(usageLimit) ? 0 : usageLimit,
      usageCount: 0,
      minOrderAmount: isNaN(minOrderAmount) ? 0 : minOrderAmount,
      validFrom,
      validTo,
      isActive,
      loopBonus, // <-- save this
    };

    let activityMessage = "";

    // ✅ Label for activity log depending on discount type
    let discountLabel = "";
    if (discountType === "percentage") {
      discountLabel = `${discountAmount}% off`;
    } else if (discountType === "flat") {
      discountLabel = `₹${discountAmount.toFixed(2)} off`;
    } else if (discountType === "time_bonus") {
      discountLabel = `Play ${baseMinutes} mins, get ${bonusMinutes} mins free`;
    }

    if (this.currentOfferId) {
      // Edit existing coupon
      const idx = this.coupons.findIndex((o) => o.id === this.currentOfferId);
      if (idx !== -1) {
        const oldCoupon = this.coupons[idx];
        const oldStatus = oldCoupon.isActive;
        this.coupons[idx] = { ...oldCoupon, ...payload };
        this.showToast("Coupon updated", "success");

        activityMessage = `💳 Updated coupon "${name}" (${code}) - ${discountLabel}`;

        if (oldStatus !== isActive) {
          activityMessage += ` | ${isActive ? "🟢 Activated" : "🔴 Deactivated"}`;
        }
      }
    } else {
      // Create new coupon
      const newId = this.coupons.length ? Math.max(...this.coupons.map((o) => o.id)) + 1 : 1;
      this.coupons.push({ id: newId, ...payload });
      this.showToast("Coupon created", "success");

      activityMessage = `💳 Created coupon "${name}" (${code}) - ${discountLabel} | ${isActive ? "🟢 Active" : "🔴 Inactive"}`;
    }

    // ✅ Log the activity in one clean line
    if (activityMessage) {
      this.logActivity(activityMessage);
    }

    this.hideModal("offer-modal");
    this.renderOffers();
  }

  showAddUserModal() {
    this.currentUserId = null;
    document.getElementById("user-modal-title").textContent = "Add New User";
    this.clearUserForm();
    this.showModal("user-modal");
  }

  showEditUserModal(userId) {
    const user = this.systemUsers.find((u) => u.id === userId);
    if (!user) return;

    this.currentUserId = userId;
    document.getElementById("user-modal-title").textContent = "Edit User";
    this.populateUserForm(user);
    this.showModal("user-modal");
  }

  clearUserForm() {
    document.getElementById("user-fullname").value = "";
    document.getElementById("user-username").value = "";
    document.getElementById("user-email").value = "";
    document.getElementById("user-phone").value = "";
    document.getElementById("user-role").value = "Staff";
    document.getElementById("user-password").value = "";
  }

  populateUserForm(user) {
    document.getElementById("user-fullname").value = user.fullName;
    document.getElementById("user-username").value = user.username;
    document.getElementById("user-email").value = user.email;
    document.getElementById("user-phone").value = user.phone;
    document.getElementById("user-role").value = user.role;
    document.getElementById("user-password").value = user.password;
  }

  saveUser() {
    const formData = {
      fullName: document.getElementById("user-fullname").value.trim(),
      username: document.getElementById("user-username").value.trim(),
      email: document.getElementById("user-email").value.trim(),
      phone: document.getElementById("user-phone").value.trim(),
      role: document.getElementById("user-role").value,
      password: document.getElementById("user-password").value.trim(),
    };

    if (!formData.fullName || !formData.username || !formData.email || !formData.password) {
      this.showToast("Please fill all required fields", "error");
      return;
    }

    // Check for duplicate username/email
    const existingUser = this.systemUsers.find((u) => (u.username === formData.username || u.email === formData.email) && u.id !== this.currentUserId);

    if (existingUser) {
      this.showToast("Username or email already exists", "error");
      return;
    }

    let activityMessage = "";

    if (this.currentUserId) {
      // Update existing user
      const userIndex = this.systemUsers.findIndex((u) => u.id === this.currentUserId);
      if (userIndex !== -1) {
        const oldUser = this.systemUsers[userIndex];
        this.systemUsers[userIndex] = { ...oldUser, ...formData };
        this.showToast("User updated successfully", "success");

        activityMessage = `👤 Updated user "${formData.fullName}" (${formData.role})`;
      }
    } else {
      // Create new user
      const newUser = {
        id: Math.max(...this.systemUsers.map((u) => u.id), 0) + 1,
        ...formData,
        isActive: true,
        lastLogin: null,
        memberSince: new Date().toISOString().split("T")[0],
      };
      this.systemUsers.push(newUser);
      this.showToast("User created successfully", "success");

      activityMessage = `➕ Created new user "${formData.fullName}" (${formData.role})`;
    }

    // ✅ Log the activity in one line
    if (activityMessage) {
      this.logActivity(activityMessage);
    }

    this.hideModal("user-modal");
    this.renderUserManagement();
  }

  deleteUser(userId) {
    const user = this.systemUsers.find((u) => u.id === userId);
    if (!user) return;

    if (userId === this.currentUser?.id) {
      this.showToast("Cannot delete currently logged in user", "error");
      return;
    }

    if (confirm(`Are you sure you want to delete user "${user.fullName}"?`)) {
      this.systemUsers = this.systemUsers.filter((u) => u.id !== userId);
      this.renderUserManagement();
      this.showToast("User deleted successfully", "success");

      // ✅ Log the deletion in activity log
      this.logActivity(`❌ Deleted user "${user.fullName}" (${user.role})`);
    }
  }

  toggleUserStatus(userId) {
    if (userId === this.currentUser?.id) {
      this.showToast("Cannot deactivate currently logged in user", "error");
      return;
    }

    const user = this.systemUsers.find((u) => u.id === userId);
    if (user) {
      user.isActive = !user.isActive;
      this.renderUserManagement();
      this.showToast(`User ${user.isActive ? "activated" : "deactivated"} successfully`, "success");
    }
  }

  showEditUserModal(userId) {
    const user = this.systemUsers.find((u) => u.id === userId);
    if (!user) return;

    this.currentUserId = userId;
    document.getElementById("user-modal-title").textContent = "Edit User";
    this.populateUserForm(user);
    this.showModal("user-modal");
  }

  showAddBranchModal() {
    this.currentBranchId = null;
    document.getElementById("branch-modal-title").textContent = "Add New Branch";
    this.clearBranchForm();
    this.showModal("branch-modal");
  }

  showEditBranchModal(branchId) {
    const branch = this.branches.find((b) => b.id === branchId);
    if (!branch) return;

    this.currentBranch = branchId;
    document.getElementById("branch-modal-title").textContent = "Edit Branch";

    document.getElementById("branch-name").value = branch.name;
    document.getElementById("branch-location").value = branch.location;
    document.getElementById("branch-address").value = branch.address;
    document.getElementById("branch-contact").value = branch.contact;
    document.getElementById("branch-manager").value = branch.manager;
    document.getElementById("branch-timing").value = branch.timing;
    document.getElementById("branch-console").value = branch.console;
    document.getElementById("branch-established").value = branch.established;
    document.getElementById("branch-active").checked = branch.isActive;
    this.showModal("branch-modal");
  }

  clearBranchForm() {
    document.getElementById("branch-name").value = "";
    document.getElementById("branch-location").value = "";
    document.getElementById("branch-address").value = "";
    document.getElementById("branch-contact").value = "";
    document.getElementById("branch-manager").value = "";
    document.getElementById("branch-timing").value = "";
    document.getElementById("branch-console").value = "";
    document.getElementById("branch-established").value = "";
    //document.getElementById('branch-manager-email').value = '';
  }

  populateBranchForm(branch) {
    document.getElementById("branch-name").value = branch.name;
    document.getElementById("branch-location").value = branch.location;
    document.getElementById("branch-address").value = branch.address;
    document.getElementById("branch-contact").value = branch.contact;
    document.getElementById("branch-manager").value = branch.manager;
    document.getElementById("branch-timing").value = branch.timing;
    document.getElementById("branch-console").value = branch.console;
    document.getElementById("branch-established").value = branch.established;
    //document.getElementById('branch-manager-email').value = branch.email;
  }

  saveBranch() {
    const formData = {
      name: document.getElementById("branch-name").value.trim(),
      location: document.getElementById("branch-location").value.trim(),
      address: document.getElementById("branch-address").value.trim(),
      contact: document.getElementById("branch-contact").value.trim(),
      manager: document.getElementById("branch-manager").value.trim(),
      timing: document.getElementById("branch-timing").value.trim(),
      console: document.getElementById("branch-console").value.trim(),
      established: document.getElementById("branch-established").value.trim(),
      isActive: document.getElementById("branch-active").checked,
    };

    if (!formData.name || !formData.location || !formData.contact || !formData.console || !formData.established) {
      this.showToast("Please fill all required fields", "error");
      return;
    }

    if (this.currentBranch) {
      // Edit existing branch
      const index = this.branches.findIndex((b) => b.id === this.currentBranch);
      if (index !== -1) {
        this.branches[index] = { ...this.branches[index], ...formData };
        this.showToast("Branch updated successfully", "success");

        // ✅ Log branch update
        this.logActivity(`✏️ Updated branch "${formData.name}"`);
      }
    } else {
      // Add new branch
      const newBranch = {
        id: Math.max(...this.branches.map((b) => b.id), 0) + 1,
        ...formData,
      };
      this.branches.push(newBranch);
      this.showToast("Branch added successfully", "success");

      // ✅ Log branch creation
      this.logActivity(`🏢 Added new branch "${formData.name}"`);
    }

    this.currentBranch = null;
    this.hideModal("branch-modal");
    this.renderBranchManagement();
  }

  deleteBranch(branchId) {
    const branch = this.branches.find((b) => b.id === branchId);
    if (!branch) return;

    if (branchId === this.currentBranch?.id) {
      this.showToast("Cannot delete currently switched Branch", "error");
      return;
    }

    if (confirm(`Are you sure you want to delete the branch "${branch.name}"? This action cannot be undone.`)) {
      this.branches = this.branches.filter((b) => b.id !== branchId);
      this.renderBranchManagement();
      this.showToast("Branch deleted successfully", "success");

      // ✅ Log branch deletion
      this.logActivity(`🗑️ Deleted branch "${branch.name}"`);
    }
  }

  // Additional placeholder methods for edit/delete functionality
  showEditGameModal(gameId) {
    const id = parseInt(gameId);
    const game = this.games.find((g) => g.id === id);
    if (!game) return this.showToast("Game not found", "error");
    this.currentGameId = id;
    const title = document.getElementById("game-modal-title");
    if (title) title.textContent = "Edit Game";
    this.populateGameForm(game);
    this.renderConsoleAssignment(game.consoles || []);
    this.showModal("add-game-modal");
  }

  renderConsoleAssignment(selectedIds = []) {
    const container = document.getElementById("console-assignment");
    if (!container) return;
    container.innerHTML = "";
    this.consoles.forEach((c) => {
      const wrap = document.createElement("div");
      wrap.className = "form-group";
      const id = `console-assign-${c.id}`;
      wrap.innerHTML = `
      <label class="form-label" for="${id}">
        <input type="checkbox" id="${id}" value="${c.id}" ${selectedIds.includes(c.id) ? "checked" : ""}/>
        ${c.name}
      </label>`;
      container.appendChild(wrap);
    });
  }

  deleteGame(gameId) {
    const id = parseInt(gameId);
    const game = this.games.find((g) => g.id === id);
    if (!game) return;

    if (!confirm(`Delete the game "${game.name}"? This action cannot be undone.`)) return;

    this.games = this.games.filter((g) => g.id !== id);
    this.renderGames();
    this.showToast("Game deleted", "success");

    // ✅ Log game deletion
    this.logActivity(`🗑️ Deleted game "${game.name}"`);
  }

  showEditOfferModal(offerId) {
    const id = parseInt(offerId, 10);
    if (isNaN(id)) {
      return this.showToast("Invalid coupon ID", "error");
    }

    const offer = this.coupons.find((o) => o.id === id);
    if (!offer) {
      return this.showToast("Coupon not found", "error");
    }

    this.currentOfferId = id;

    const titleEl = document.getElementById("offer-modal-title");
    if (titleEl) {
      titleEl.textContent = "Edit Coupon";
    }

    this.populateOfferForm(offer);
    this.showModal("offer-modal");
  }

  populateOfferForm(offer) {
    const set = (id, val) => {
      const el = document.getElementById(id);
      if (el) el.value = val ?? "";
    };

    // ✅ Basic fields
    set("offer-name", offer.name);
    set("offer-code", offer.code);
    set("offer-description", offer.description);
    set("offer-discount-type", offer.discountType);

    // ✅ Discount-specific fields
    if (offer.discountType === "percentage") {
      set("offer-discount-amount", offer.discountAmount ?? 0);
    } else if (offer.discountType === "flat") {
      set("offer-discount-amount", (offer.discountAmount ?? 0).toFixed(2));
    } else if (offer.discountType === "time_bonus") {
      set("offer-base-minutes", offer.baseMinutes ?? 120); // default 2h
      set("offer-bonus-minutes", offer.bonusMinutes ?? 30); // default 30m
      const loop = document.getElementById("offer-loop-bonus");
      if (loop) loop.checked = !!offer.loopBonus; // ✅ restore checkbox
    }

    // ✅ Other meta info
    set("offer-usage-limit", offer.usageLimit ?? 0);
    set("offer-min-order-amount", offer.minOrderAmount ?? 0);

    // ✅ Dates (format: yyyy-mm-dd)
    set("offer-valid-from", offer.validFrom ? new Date(offer.validFrom).toISOString().slice(0, 10) : "");
    set("offer-valid-to", offer.validTo ? new Date(offer.validTo).toISOString().slice(0, 10) : "");

    // ✅ Active checkbox
    const active = document.getElementById("offer-active");
    if (active) active.checked = !!offer.isActive;

    const loopBonusEl = document.getElementById("offer-loop-bonus");
    if (loopBonusEl) loopBonusEl.checked = !!offer.loopBonus;

    // ✅ Show correct UI fields
    this.toggleDiscountFields();
  }

  deleteOffer(offerId) {
    const id = parseInt(offerId);
    const offer = this.coupons.find((o) => o.id === id);
    if (!offer) return;

    if (!confirm(`Delete the coupon "${offer.name}"? This action cannot be undone.`)) return;

    this.coupons = this.coupons.filter((o) => o.id !== id);
    this.renderOffers();
    this.showToast("Coupon deleted", "success");

    // ✅ Log coupon deletion
    this.logActivity(`🗑️ Deleted coupon "${offer.name}" (${offer.code})`);
  }

  toggleOffer(offerId) {
    const id = parseInt(offerId);
    const offer = this.coupons.find((o) => o.id === id);
    if (!offer) return;
    offer.isActive = !offer.isActive;
    this.renderOffers();
    this.showToast(offer.isActive ? "Coupon activated" : "Coupon paused", "success");
  }

  saveGame() {
    const name = document.getElementById("game-name")?.value?.trim();
    const category = document.getElementById("game-category")?.value?.trim();
    const developer = document.getElementById("game-developer")?.value?.trim();
    const rating = parseFloat(document.getElementById("game-rating")?.value || "0");
    const releaseDate = document.getElementById("game-release")?.value || "";
    const consoles = Array.from(document.querySelectorAll('#console-assignment input[type="checkbox"]:checked')).map((i) => parseInt(i.value));

    if (!name) return this.showToast("Game name is required", "error");

    const payload = {
      name,
      category: category || "-",
      developer: developer || "-",
      rating: isNaN(rating) ? 0 : rating,
      releaseDate,
      consoles,
    };

    if (this.currentGameId) {
      // update
      const idx = this.games.findIndex((g) => g.id === this.currentGameId);
      if (idx !== -1) {
        this.games[idx] = { ...this.games[idx], ...payload };
        this.showToast("Game updated", "success");

        // ✅ Log activity
        this.logActivity(`✏️ Updated game "${name}"`);
      }
    } else {
      // insert
      const newId = this.games.length ? Math.max(...this.games.map((g) => g.id)) + 1 : 1;
      this.games.push({ id: newId, ...payload });
      this.showToast("Game added", "success");

      // ✅ Log activity
      this.logActivity(`➕ Added new game "${name}"`);
    }

    this.hideModal("add-game-modal");
    this.renderGames();
  }
}

// Initialize the application
let app;
if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", () => {
    console.log("DOM loaded, initializing GameBot Gaming Cafe App");
    app = new GameCafeApp();
  });
} else {
  console.log("DOM already loaded, initializing GameBot Gaming Cafe App");
  app = new GameCafeApp();
}
