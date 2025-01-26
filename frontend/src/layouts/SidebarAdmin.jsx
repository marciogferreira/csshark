import { Link } from 'react-router-dom'
function SidebarAdmin() {
    return (
        <>
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
           <Link to="/modalidades" className="dropdown-item">
               Modalidades
           </Link>
       </li>
       <li>
           <Link to="/treinos" className="dropdown-item">
               Treinos
           </Link>
       </li>
       <li className='mb-5'>
           <Link to="/turmas" className="dropdown-item">
               Turmas
           </Link>
       </li>
       <li>
           <Link to="/matriculas" className="dropdown-item">
               Matrículas
           </Link>
       </li>
       <li className="nav-item ">
           <Link to="/financeiro" className="nav-link">
               Financeiro
           </Link>
       </li>

       <li className='mb-5'>
           <Link to={"/usuarios"} className="dropdown-item">
               Usuários
           </Link>
       </li>
   </>
    )
}

export default SidebarAdmin;