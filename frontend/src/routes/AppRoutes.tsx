import { BrowserRouter, Route, Routes } from "react-router-dom";
import LoginPage from "../pages/LoginPage";
import NotFoundPage from "../pages/NotFoundPage";
import DashboardPage from "../pages/DashboardPage";
import UsuariosIndex from "../pages/UsuariosPage/UsuariosIndex";
import UsuariosForm from "../pages/UsuariosPage/UsuariosForm";
import { Component, ReactNode } from "react";
import LayoutDefault from "../layouts/LayoutDefault";

export default function AppRoutes() {

  function getLayout(component: ReactNode) {
    return <LayoutDefault>{component}</LayoutDefault>
  }

   return(
      <>
        <BrowserRouter>
          <Routes>
            <Route path="/" element={<LoginPage />} />
            <Route path="/painel" element={getLayout(<DashboardPage />)} />


            <Route path="/usuarios" element={getLayout(<UsuariosIndex />)} />
            <Route path="/usuarios/novo" element={getLayout(<UsuariosForm />)} />
            <Route path="/usuarios/editar/:id" element={getLayout(<UsuariosForm />)} />

            <Route path="*" element={getLayout(<NotFoundPage />)} />
          </Routes>
        </BrowserRouter>
      </>
    )
}