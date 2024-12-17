// スマホとパソコンで画面変える
resize();

window.addEventListener("resize", () => {
    resize();
});

// 短縮リンク作成のリクエスト
create_ele.pc.button.addEventListener("click", () => {
    if (create_ele.pc.id.value !== "" && create_ele.pc.url !== "" && create_ele.pc.password !== "") {
        create(create_ele.pc.id.value, create_ele.pc.url.value, create_ele.pc.password.value);
    }
});

create_ele.ph.button.addEventListener("click", () => {
    if (create_ele.ph.id.value !== "" && create_ele.ph.url !== "" && create_ele.ph.password !== "") {
        create(create_ele.ph.id.value, create_ele.ph.url.value, create_ele.ph.password.value);
    }
});

// 短縮リンク削除のリクエスト
delete_ele.pc.button.addEventListener("click", () => {
    if (delete_ele.pc.id.value !== "" && delete_ele.pc.url !== "" && delete_ele.pc.password !== "") {
        del(delete_ele.pc.id.value, delete_ele.pc.password.value);
    }
});

delete_ele.ph.button.addEventListener("click", () => {
    if (delete_ele.ph.id.value !== "" && delete_ele.ph.password !== "") {
        del(delete_ele.ph.id.value, delete_ele.ph.password.value);
    }
});