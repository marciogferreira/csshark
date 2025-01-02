import { useContext } from "react";
import { Link } from "react-router-dom";
import AuthContext from "../contexts/AuthContext";

const Sidebar = () => {
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
                
                <li className="nav-item dropdown">
                    <a className="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Cadastros
                    </a>
                    <ul className="dropdown-menu">
                        <li>
                            <Link to="/alunos" className="dropdown-item">
                                Alunos
                            </Link>
                        </li>
                        <li>
                            <Link to="/professores" className="dropdown-item">
                                Professores
                            </Link>
                        </li>
                        <li>
                            <Link to={"/usuarios"} className="dropdown-item">
                                Usuários
                            </Link>
                        </li>
                        <li><hr className="dropdown-divider" /></li>
                        <li>
                            <Link to="/modalidades" className="dropdown-item">
                                Modalidades
                            </Link>
                        </li>
                       
                        <li>
                            <Link to="/treinos" className="dropdown-item">
                                Treinos
                            </Link>
                        </li>

                                          
                        
                    </ul>
                </li>
                <li className="nav-item dropdown">
                    <a className="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Secretaria
                    </a>
                    <ul className="dropdown-menu">
                        <li>
                            <Link to="/turmas" className="dropdown-item">
                                Turmas
                            </Link>
                        </li>
                        <li>
                            <Link to="/matriculas" className="dropdown-item">
                                Matrículas
                            </Link>
                        </li>
                        <li>
                            <Link to="/treinos-alunos" className="dropdown-item">
                                Treinos de Alunos
                            </Link>
                        </li>

                        <li><hr className="dropdown-divider" /></li>
                        
                    </ul>
                </li>
                <li className="nav-item">
                    <Link to="/financeiro" className="nav-link">
                        Financeiro
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

export default Sidebar;
