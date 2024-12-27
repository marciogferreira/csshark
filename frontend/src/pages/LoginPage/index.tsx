import React from "react";
import { useNavigate } from "react-router-dom";


const LoginPage = () => {
  const navigate = useNavigate();
  return (
    <div className="d-flex justify-content-center align-items-center vh-100 bg-light">
      <div className="card p-4 shadow" style={{ width: "350px" }}>
        <div className="text-center mb-4">
          <img
            src="logo.png"
            alt="Logo"
            style={{ width: "150px", height: "auto" }}
            className="mb-3"
          />
          <h5>Bem-vindo(a) à Academia</h5>
        </div>
        <form>
          <div className="mb-3">
            <label htmlFor="email" className="form-label">
              Email
            </label>
            <input
              type="email"
              id="email"
              className="form-control"
              placeholder="Digite seu email"
            />
          </div>
          <div className="mb-3">
            <label htmlFor="senha" className="form-label">
              Senha
            </label>
            <input
              type="password"
              id="senha"
              className="form-control"
              placeholder="Digite sua senha"
            />
          </div>
          <div className="mb-3 text-end">
            <a href="/recuperar-senha" className="text-decoration-none">
              Esqueceu sua senha?
            </a>
          </div>
        <button onClick={() => navigate('/Painel')} type="button" className="btn btn-primary w-100">
            Entrar
          </button>
        </form>
        <div className="text-center mt-3">
          <button className="btn btn-outline-secondary w-100" onClick={() => window.location.href = "/novo-aluno"}>
            Novo Aluno
          </button>
        </div>
      </div>
    </div>
  );
};

export default LoginPage;
