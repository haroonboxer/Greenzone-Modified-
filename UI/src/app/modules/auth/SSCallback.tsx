import { useEffect } from "react";
import { useNavigate } from "react-router-dom";
import axios from "axios";
// import api from '..//..//..//api/api'
// import {getCurrentUser,hasPermission} from "./AuthHelper";
 import { AuthModel,JwtPayload } from "./core/_models";
import {useAuth} from "../auth/core/Auth";
// import {getUserByToken}from'./core/_requests'
import { jwtDecode } from "jwt-decode";
const SSOCallback = () => {

     const {saveAuth, setCurrentUser} = useAuth();
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

                    const decoded = jwtDecode<JwtPayload>(token);   
                    console.log(decoded);
                    saveAuth({
                        api_token: token,
                        expires_at: new Date(decoded.exp * 1000).toISOString(), 
                    });

                    // navigate("/dashboard");
                } catch (error) {
                    console.error(error);
                }
            };

            authenticate();
        }, []);

    return <div>
      <p>Signing in...</p> 
    </div>;

};

export default SSOCallback;