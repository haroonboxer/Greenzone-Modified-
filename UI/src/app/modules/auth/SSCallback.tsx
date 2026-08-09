 
import { useEffect } from "react";
import { useNavigate } from "react-router-dom";
import axios from "axios";
import { jwtDecode } from "jwt-decode";

import { useAuth } from "../auth/core/Auth";
import { JwtUserModel } from "./core/_models";

const SSOCallback = () => {
    const { saveAuth, setCurrentUser } = useAuth();
    const navigate = useNavigate();

    useEffect(() => {
        const authenticate = async () => {
            try {
           

                const response = await axios.get(
                    "http://127.0.0.1:8000/sso/token",
                    {
                        withCredentials: true,
                    }
                );

                const token = response.data.token;

                if (!token) {
                    throw new Error("No token received from SSO");
                }

         

                // Decode JWT
                const decoded = jwtDecode<JwtUserModel>(token);

                 

                // Make sure the JWT has an expiration
                if (!decoded.exp) {
                    throw new Error("JWT does not contain an expiration time");
                }

                // Check whether JWT is already expired
                if (decoded.exp * 1000 <= Date.now()) {
                    throw new Error("SSO token has expired");
                }

                // Save authentication information.
                // saveAuth() -> AuthHelpers.setAuth()
                // -> localStorage key: kt-auth-react-v
                saveAuth({
                    api_token: token,
                    expires_at: new Date(decoded.exp * 1000).toISOString(),
                });

                // Restore current user in React state
                setCurrentUser(decoded);

             

                // Navigate to dashboard
                navigate("/dashboard", { replace: true });

            } catch (error) {
                console.error("SSO Login Failed:", error);
            }
        };

        authenticate();
    }, [navigate, saveAuth, setCurrentUser]);

    return (
        <div>
            <p>Signing in...</p>
        </div>
    );
};

export default SSOCallback;
 
