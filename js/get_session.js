//receive session user_id, username, role from ajax call
async function get_session() {
    try {
        result = await $.ajax({
            url: `${base_url}/users/get_session`,
            type: "POST",
            done: (response) => {
                return response;
            },
            fail: () => {
                console.log("failed to fetch user data, please log in");
            }
        });
        return result;
    } catch (err) {
        console.log('fetch failed', err);
    }
}