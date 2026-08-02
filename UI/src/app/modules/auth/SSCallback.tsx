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

                // Decode the JWT
               const decoded = jwtDecode<JwtUserModel>(token);

                saveAuth({
                    api_token: token,
                    expires_at: new Date(decoded.exp * 1000).toISOString(),
                });
                 
                setCurrentUser(decoded);

                navigate("/dashboard");

        

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