import { useContext } from "react";
import { Link } from "react-router-dom";
import AuthContext from "../contexts/AuthContext";

const SidebarAluno = () => {
const { handleLogout } = useContext(AuthContext);

  return (
    <nav className="navbar navbar-expand-lg">
        <div className="container-fluid">
            <a className="navbar-brand" href="#">CShark</a>
            <button className="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span className="navbar-toggler-icon"></span>
            </button>
            <div className="collapse navbar-collapse" id="navbarSupportedContent">
            <ul className="navbar-nav me-auto mb-2 mb-lg-0">
                <li className="nav-item">
                    
                    <Link to="/" className="nav-link active">
                        Painel
                    </Link>
                </li>
                
                
                <li className="nav-item">
                    <Link to="/ficha" className="nav-link">
                        Ficha de Treino
                    </Link>
                </li>

                <li className="nav-item">
                    <a onClick={() => handleLogout()} href="#" className="nav-link">
                        Sair
                    </a>
                </li>   
            </ul>
            
            </div>
        </div>
    </nav>
  );
};

export default SidebarAluno;
