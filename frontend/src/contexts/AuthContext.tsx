import { createContext, ReactNode, useEffect, useState } from 'react';
import Util from '../core/Util';
import Api from '../core/api';

type DataContext = {
    isLogged: boolean,
    setIsLogged: (data: any) => void,
    handleLogout: () => void,
    user: any,
    setUser: (data: any) => void,
    signIn: (data: any) => void,
    showMenu: boolean,
    setShowMenu: (data: any) => void,
    loading: boolean,
    setLoading: (data: any) => void,
    show: boolean,
    setShow: (data: any) => void
}

const AuthContext = createContext({} as DataContext);

type DataProps = {
    children: ReactNode
}

type DataUser = {
    name: string,
    email: string,
    role: any,
}

export const AuthProvider = ({ children }: DataProps) => {

    const [isLogged, setIsLogged] = useState(false);
    const [user, setUser] = useState({} as DataUser);
    const [show, setShow] = useState(false);
    const [loading, setLoading] = useState(false);
    async function getUser() { 
        console.log(Util.getUser())
        if(Util.getUser()) {
             setUser(Util.getUser());
        } else {
            const response = await Api.post('me');
            Util.setUser(response.data);
            setUser(response.data);
        }
    }

    async function signIn(token: string) {
        await Util.setToken(token)
        await getUser();
        setIsLogged(true);
        await getUser();
    }

    async function handleLogout() {
        Util.removeToken();
        Util.removeUser();
        setUser({ name: '', email: '', role: null });
        setIsLogged(false);
        
    }

    useEffect(() => {
        const token = Util.getToken();
        if(token) {
            setIsLogged(true);
            getUser()
        }
    }, []);

    console.log(user)

    return (
        <AuthContext.Provider value={
            { 
                isLogged,
                setIsLogged,
                handleLogout,
                signIn,
                user,
                setUser,
                show, 
                setShow,
                loading,
                setLoading,
            } as any}>
            {children}
        </AuthContext.Provider>
    )
}

export default AuthContext;
