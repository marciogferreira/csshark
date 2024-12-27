Segue uma modelagem de banco de dados que atende aos requisitos para um sistema de academia. A estrutura foi organizada para manter a normalização, facilitar consultas e garantir escalabilidade.
---

### **Tabelas e Relacionamentos**

1. **Tabela: usuarios**  
   - `id_usuario` (PK) - INT AUTO_INCREMENT  
   - `nome` - VARCHAR(100)  
   - `email` - VARCHAR(100)  
   - `senha` - VARCHAR(255)  
   - `tipo_usuario` - ENUM('admin', 'instrutor', 'atendente')  
   - `data_criacao` - DATETIME  

2. **Tabela: alunos**  
   - `id_aluno` (PK) - INT AUTO_INCREMENT  
   - `nome` - VARCHAR(100)  
   - `cpf` - VARCHAR(11)  
   - `data_nascimento` - DATE  
   - `telefone` - VARCHAR(15)  
   - `email` - VARCHAR(100)  
   - `endereco` - TEXT  
   - `data_matricula` - DATETIME  
   - `id_usuario_responsavel` (FK) - INT  

3. **Tabela: modalidades**  
   - `id_modalidade` (PK) - INT AUTO_INCREMENT  
   - `nome` - VARCHAR(50)  
   - `descricao` - TEXT  
   - `valor_mensal` - DECIMAL(10, 2)  

4. **Tabela: exercicios**  
   - `id_exercicio` (PK) - INT AUTO_INCREMENT  
   - `nome` - VARCHAR(100)  
   - `descricao` - TEXT  
   - `grupo_muscular` - VARCHAR(50)  

5. **Tabela: sequencias_exercicios**  
   - `id_sequencia` (PK) - INT AUTO_INCREMENT  
   - `nome` - VARCHAR(100)  
   - `descricao` - TEXT  
   - `id_aluno` (FK) - INT  

6. **Tabela: sequencia_exercicio_detalhes**  
   - `id_detalhe` (PK) - INT AUTO_INCREMENT  
   - `id_sequencia` (FK) - INT  
   - `id_exercicio` (FK) - INT  
   - `series` - INT  
   - `repeticoes` - INT  
   - `carga` - DECIMAL(5, 2)  

7. **Tabela: controle_financeiro**  
   - `id_transacao` (PK) - INT AUTO_INCREMENT  
   - `id_aluno` (FK) - INT  
   - `tipo` - ENUM('entrada', 'saida')  
   - `descricao` - TEXT  
   - `valor` - DECIMAL(10, 2)  
   - `data` - DATETIME  

8. **Tabela: categorias_produtos**  
   - `id_categoria` (PK) - INT AUTO_INCREMENT  
   - `nome` - VARCHAR(50)  
   - `descricao` - TEXT  

9. **Tabela: produtos**  
   - `id_produto` (PK) - INT AUTO_INCREMENT  
   - `nome` - VARCHAR(100)  
   - `descricao` - TEXT  
   - `preco` - DECIMAL(10, 2)  
   - `id_categoria` (FK) - INT  
   - `estoque` - INT  

10. **Tabela: vendas**  
    - `id_venda` (PK) - INT AUTO_INCREMENT  
    - `id_usuario` (FK) - INT  
    - `data` - DATETIME  
    - `valor_total` - DECIMAL(10, 2)  

11. **Tabela: venda_detalhes**  
    - `id_detalhe` (PK) - INT AUTO_INCREMENT  
    - `id_venda` (FK) - INT  
    - `id_produto` (FK) - INT  
    - `quantidade` - INT  
    - `preco_unitario` - DECIMAL(10, 2)  

---

### **Relacionamentos**
- **usuarios ↔ alunos**: `id_usuario_responsavel` em **alunos** é FK para **usuarios**.
- **modalidades** é independente, mas pode ser usada para criar matrículas em outro contexto.
- **exercicios** é usado por **sequencias_exercicios** via **sequencia_exercicio_detalhes**.
- **categorias_produtos ↔ produtos**: Uma categoria pode ter vários produtos.
- **produtos ↔ vendas**: Relacionamento via **venda_detalhes** para controlar itens vendidos.

Se precisar de ajustes ou adicionar mais funcionalidades, é só avisar!