import React, { useState } from "react";
import "bootstrap/dist/css/bootstrap.min.css";
import { useNavigate } from "react-router-dom";

const UsuariosIndex = () => {
  const navigate = useNavigate();
  const [usuarios, setUsuarios] = useState([
    {
      id: 1,
      nome: "João Silva",
      email: "joao@email.com",
      tipo_usuario: "admin",
      data_criacao: "2024-12-01 12:00:00",
    },
    {
      id: 2,
      nome: "Maria Oliveira",
      email: "maria@email.com",
      tipo_usuario: "instrutor",
      data_criacao: "2024-12-05 14:30:00",
    },
  ]);

  const [searchTerm, setSearchTerm] = useState("");

  // Função para deletar usuário
  const handleDelete = (id) => {
    if (window.confirm("Deseja realmente excluir este usuário?")) {
      setUsuarios(usuarios.filter((usuario) => usuario.id !== id));
    }
  };

  // Função para editar (aqui apenas uma simulação)
  const handleEdit = (id) => {
    alert(`Editar usuário com ID: ${id}`);
  };

  // Filtrando usuários com base no termo de pesquisa
  const filteredUsuarios = usuarios.filter((usuario) =>
    usuario.nome.toLowerCase().includes(searchTerm.toLowerCase())
  );

  return (
    <div className="container mt-5">
      <div className="d-flex justify-content-between align-items-center mb-4">
        <h2>Lista de Usuários</h2>
        <button onClick={() => navigate('/usuarios/novo')} className="btn btn-primary">Novo Usuário</button>
      </div>

      {/* Campo de Pesquisa */}
      <div className="mb-3">
        <input
          type="text"
          className="form-control"
          placeholder="Pesquisar por nome..."
          value={searchTerm}
          onChange={(e) => setSearchTerm(e.target.value)}
        />
      </div>

      {/* Tabela */}
      <table className="table table-striped">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Tipo de Usuário</th>
            <th>Data de Criação</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          {filteredUsuarios.length > 0 ? (
            filteredUsuarios.map((usuario) => (
              <tr key={usuario.id}>
                <td>{usuario.id}</td>
                <td>{usuario.nome}</td>
                <td>{usuario.email}</td>
                <td>{usuario.tipo_usuario}</td>
                <td>{usuario.data_criacao}</td>
                <td>
                  <button
                    className="btn btn-warning btn-sm me-2"
                    onClick={() => handleEdit(usuario.id)}
                  >
                    Editar
                  </button>
                  <button
                    className="btn btn-danger btn-sm"
                    onClick={() => handleDelete(usuario.id)}
                  >
                    Excluir
                  </button>
                </td>
              </tr>
            ))
          ) : (
            <tr>
              <td colSpan="6" className="text-center">
                Nenhum usuário encontrado.
              </td>
            </tr>
          )}
        </tbody>
      </table>
    </div>
  );
};

export default UsuariosIndex;
