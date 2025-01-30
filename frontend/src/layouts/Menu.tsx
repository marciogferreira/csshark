import {
    Drawer,
    Card
  } from "@material-tailwind/react";
import { Link } from "react-router-dom";
import { useContext } from "react";
import AuthContext from "../contexts/AuthContext";
import SidebarAluno from './SidebarAluno';
import Profile from "../components/Profile";
import logo from '../assets/logo.png'
import SidebarAdmin from "./SidebarAdmin";


type MenuProps = {
    isDrawerOpen: boolean;
    closeDrawer: () => void;
    openAlert: boolean;
    setOpenAlert: (value: boolean) => void;
    handleOpen: (id: number) => void;
    open: number;
};

export default function Menu({ isDrawerOpen, closeDrawer }: MenuProps) {

    const { handleLogout, user } = useContext(AuthContext); 
    return (
        <div  style={{  }}>
            <Drawer id="menu-sidebar" open={isDrawerOpen} onClose={closeDrawer} placeholder={undefined} onPointerEnterCapture={null} onPointerLeaveCapture={undefined}>
                <>
                    <Card
                        placeholder={undefined} onPointerEnterCapture={null} onPointerLeaveCapture={undefined}
                        variant="filled"
                        color="transparent"
                        shadow={false}
                        className="h-[calc(100vh-2rem)] w-full p-4"
                        >
                        <>
                            <div className="d-flex justify-content-between">
                                <Profile />
                                <img src={logo} alt="" className="mb-3" width="60px" />
                            </div>
                            
                            
                            <hr className="my-2 border-blue-gray-50" />
                            <ul id="menu">
                                <li className="nav-item">
                                    <Link onClick={() => closeDrawer()} to="/" className="nav-link active">
                                        Painel
                                    </Link>
                                </li>
                                {user.role === '1' && <SidebarAdmin closeDrawer={closeDrawer} />}                    
                                {user.role === '2' && <SidebarAluno closeDrawer={closeDrawer} />}                    
                                <li className="nav-item">
                                    <a onClick={() => handleLogout()} href="#" className="nav-link">
                                        Sair
                                    </a>
                                </li>
                            </ul>
                        </>
                    </Card>
                    <div className="flex justify-center">
                        
                    </div>
                </>
            </Drawer>
        </div>
    )
}