import { BrowserRouter, Route, Routes } from "react-router-dom";
import LoginPage from "../pages/LoginPage";
import NotFoundPage from "../pages/NotFoundPage";
import DashboardPage from "../pages/DashboardPage";
import UsuariosIndex from "../pages/UsuariosPage/UsuariosIndex";
import UsuariosForm from "../pages/UsuariosPage/UsuariosForm";
import { ReactNode } from "react";
import LayoutDefault from "../layouts/LayoutDefault";
import AlunosIndex from "../pages/AlunosPage/AlunosIndex";
import ProfessoresIndex from "../pages/ProfessoresPage/ProfessoresIndex";
import ModalidadesIndex from "../pages/ModalidadesPage/ModalidadesIndex";
import TurmasIndex from "../pages/TurmasPage/TurmasIndex";
import AlunosTurmasIndex from "../pages/AlunosTurmasPage/AlunosTurmasIndex";
import AlunosTreinosIndex from "../pages/AlunosTreinosPage/AlunosTreinosIndex";
import FinanceiroIndex from "../pages/FinanceiroPage";
import TreinosIndex from "../pages/TreinosPage/TreinosIndex";
import FichaTreinoPage from "../pages/FichaTreinoPage";
import NovoAlunoPage from "../pages/NovoAlunoPage";
import ModalidadesAlunosPages from "../pages/ModalidadesAlunosPage";
import Professores from "../pages/Professores";
import ModelosTreinosIndex from "../pages/ModelosTreinosPage/ModelosTreinosIndex";
import AvaliacoesPage from "../pages/AvaliacoesPage";

export default function AppRoutes() {

  function getLayout(component: ReactNode) {
    return <LayoutDefault>{component}</LayoutDefault>
  }

   return(
      <>
        <BrowserRouter>
          <Routes>
          <Route path="/login" element={<LoginPage />} />
          <Route path="/" element={getLayout(<DashboardPage />)} />

          <Route path="/usuarios" element={getLayout(<UsuariosIndex />)} />
          <Route path="/usuarios/novo" element={getLayout(<UsuariosForm />)} />
          <Route path="/usuarios/editar/:id" element={getLayout(<UsuariosForm />)} />

          <Route path="/alunos" element={getLayout(<AlunosIndex />)} />

          <Route path="/professores" element={getLayout(<ProfessoresIndex />)} />
          <Route path="/modalidades" element={getLayout(<ModalidadesIndex />)} />
          <Route path="/treinos" element={getLayout(<TreinosIndex />)} />
          <Route path="/modelos-treinos" element={getLayout(<ModelosTreinosIndex />)} />
          <Route path="/turmas" element={getLayout(<TurmasIndex />)} />
          <Route path="/matriculas" element={getLayout(<AlunosTurmasIndex />)} />
          <Route path="/treinos-alunos" element={getLayout(<AlunosTreinosIndex />)} />           
        
          <Route path="/financeiro" element={getLayout(<FinanceiroIndex />)} />
          <Route path="/ficha" element={getLayout(<FichaTreinoPage />)} />
          <Route path="/aluno/novo" element={<NovoAlunoPage />} />
          <Route path="/aluno/modalidades" element={getLayout(<ModalidadesAlunosPages />)} />
          <Route path="/professores/equipe" element={getLayout(<Professores />)} />

          <Route path="/avaliacoes" element={getLayout(<AvaliacoesPage />)} />
          
          
          
          <Route path="*" element={getLayout(<NotFoundPage />)} />
        </Routes>
      </BrowserRouter>
    </>
  )
}