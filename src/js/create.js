const create = ((id, url, pass) => {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "https://api.t20.jp");

    const formData = new FormData();
    formData.append("id", id);
    formData.append("url", url);
    formData.append("password", pass);

    xhr.send(formData);

    xhr.onloadend = (() => {
        console.log(xhr.responseText);
        const res = JSON.parse(xhr.responseText);

        allHidden();
        
        if (res['success']) {
            // alert('URLの短縮に成功しました\nURL: https://t20.jp/' + id);

            const s_url = "https://t20.jp/" + id;

            if (isMobile()) {
                success_ele.ph.cre.url.innerText = s_url;
                success_ele.ph.cre.url.href  = s_url;
                success_ele.ph.cre.ele.classList.remove("hidden");
            } else {
                success_ele.pc.cre.url.innerText = s_url;
                success_ele.pc.cre.url.href  = s_url;
                success_ele.pc.cre.ele.classList.remove("hidden");
            }
        } else {
            // alert('URLの短縮に失敗しました\nそのIDは既に使われている可能性がございます')

            if (isMobile()) {
                fail_ele.ph.cre.classList.remove('hidden');
            } else {
                fail_ele.pc.cre.classList.remove('hidden');
            }
        }
    });
});