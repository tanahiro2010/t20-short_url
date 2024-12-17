const del = ((id, password) => {
    const xhr = new XMLHttpRequest();
    xhr.open("DELETE", "https://api.t20.jp");
    xhr.setRequestHeader('Content-Type', 'application/json');

    const data = {
        'id': id,
        'password': password
    };

    // const formData = new FormData();
    // formData.append("id", id);
    // formData.append("password", pass);

    xhr.send(JSON.stringify(data));

    xhr.onloadend = (() => {
        console.log(xhr.responseText);
        const res = JSON.parse(xhr.responseText);
        
        allHidden();
        
        if (res['success']) {
            // alert('URLの削除に成功しました');

            if (isMobile()) {
                success_ele.ph.del.classList.remove("hidden");
            } else {
                success_ele.pc.del.classList.remove("hidden");
            }
        } else {
            // alert('URLの短縮に失敗しました\nそのIDは既に使われている可能性がございます');

            if (isMobile()) {
                fail_ele.ph.del.classList.remove('hidden');
            } else {
                fail_ele.pc.del.classList.remove('hidden');
            }
        }
    });

    // fetch("https://api.t20.jp/resource", {
    //     method: "DELETE",
    //     headers: {
    //         "Content-Type": "application/json",
    //     },
    //     body: "id=12345&name=example",
    // });
});