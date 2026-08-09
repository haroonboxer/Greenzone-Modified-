import axios from "axios";
import { jwtDecode } from "jwt-decode";
 

export interface Permission {
    ClaimType: string;
    ClaimValue: string;
}

 

export const getCurrentUser = () => {

    const token = localStorage.getItem("api_token");
    
    if (!token)
        return null;

    const user = jwtDecode<any>(token);
   
   

    return user;
};
 export const hasPermission = (permission: string): boolean => {

    const user = getCurrentUser();
    
    if (!user)
        return false;

    return user.claims.some(
        (p: any) =>
            p.ClaimType === permission &&
            p.ClaimValue === "True"
    );
};