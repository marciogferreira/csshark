import { useContext, useState } from "react";
import { Link } from "react-router-dom";
import AuthContext from "../contexts/AuthContext";
import SidebarAdmin from './SidebarAdmin';
import SidebarAluno from "./SidebarAluno";

const Sidebar = () => {
    const { user, handleLogout, show, setShow } = useContext(AuthContext);

    return (
        <>
        <div className="header">
            <div className="p-3">
                <i className="bi bi-list" onClick={() => setShow(!show)}></i>        
            </div>
        </div>
        <div className={`menu-drawer p-4 ${show ? 'drawer-show': 'drawer-hide'}`}>
            <div className="p-1">
                <div className="d-flex justify-content-between">
                    <div>
                        <h3>Complexo Shark</h3>
                        <strong>Olá, {user.name}</strong>
                    </div>
                    <div>
                        <i className="bi bi-x-lg text-white" onClick={() => setShow(false)}></i>
                    </div>
                </div>
                <hr />
                <ul>
                    <li className="nav-item">
                    
                        <Link to="/" className="nav-link active">
                            Painel
                        </Link>
                    </li>
                    {user.role === '1' && <SidebarAdmin />}                    
                    {user.role === '2' && <SidebarAluno />}                    
                    <li className="nav-item">
                        <a onClick={() => handleLogout()} href="#" className="nav-link">
                            Sair
                        </a>
                    </li>

                </ul>
            </div>
        </div>
        </>
    );
}

export default Sidebar;
