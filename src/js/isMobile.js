function isMobile() {
    return window.innerWidth <= 768; // 768px以下をスマホと判定
}

function resize() {
    if (isMobile()) { // スマホの画面
        phone_screen.classList.remove("hidden");
        pc_screen.classList.add("hidden");
    } else {          // パソコンの画面
        pc_screen.classList.remove("hidden");
        phone_screen.classList.add("hidden");
    }
}