export const login = (): void => {

    window.location.href =
    "https://your-sso-url/login?returnUrl=http://localhost:5173/auth/callback";

};



export const logout = (): void => {

    localStorage.removeItem("access_token");

    window.location.href ="https://your-sso-url/logout";

};



export const getToken = (): string | null => {

    return localStorage.getItem(
        "access_token"
    );

};