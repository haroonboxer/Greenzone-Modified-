 
import { Logout } from "../Logout";
import { AuthModel } from "./_models";

const AUTH_LOCAL_STORAGE_KEY = "kt-auth-react-v";

/**
 * Get authentication information from localStorage
 */
const getAuth = (): AuthModel | undefined => {
    try {
        const lsValue = localStorage.getItem(AUTH_LOCAL_STORAGE_KEY);

        if (!lsValue) {
            return undefined;
        }

        return JSON.parse(lsValue) as AuthModel;
    } catch (error) {
        console.error("AUTH LOCAL STORAGE PARSE ERROR", error);
        return undefined;
    }
};

/**
 * Save authentication information to localStorage
 */
const setAuth = (auth: AuthModel) => {
    try {
        localStorage.setItem(
            AUTH_LOCAL_STORAGE_KEY,
            JSON.stringify(auth)
        );

 
    } catch (error) {
        console.error("AUTH LOCAL STORAGE SAVE ERROR", error);
    }
};

/**
 * Remove authentication information from localStorage
 */
const removeAuth = () => {
    try {
        localStorage.removeItem(AUTH_LOCAL_STORAGE_KEY);

     
    } catch (error) {
        console.error("AUTH LOCAL STORAGE REMOVE ERROR", error);
    }
};

/**
 * Configure Axios
 */
export function setupAxios(axios: any) {
    axios.defaults.headers.Accept = "application/json";

    axios.interceptors.request.use(
        (config: any) => {
            const auth = getAuth();

            if (auth?.api_token) {

                // Add JWT to every API request
                config.headers = config.headers || {};

                config.headers.Authorization =
                    `Bearer ${auth.api_token}`;

           

                // Check expiration
                if (auth.expires_at) {
                    const tokenExpirationDate =
                        new Date(auth.expires_at);

                    const currentDate = new Date();

                    if (tokenExpirationDate <= currentDate) {

                     

                        removeAuth();

                        Logout();

                        return Promise.reject(
                            new Error("Authentication token expired")
                        );
                    }
                }
            }

            return config;
        },

        (error: any) => {
            return Promise.reject(error);
        }
    );
}

export {
    getAuth,
    setAuth,
    removeAuth,
    AUTH_LOCAL_STORAGE_KEY,
};
 
