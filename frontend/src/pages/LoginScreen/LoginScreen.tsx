import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { motion } from 'framer-motion';

const LoginScreen = () => {
  const [login, setLogin] = useState('');
  const [senha, setSenha] = useState('');
  const navigate = useNavigate();

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    navigate('/dashboard');
  };

  return (
    <div className="min-h-screen bg-[#003049]">
      <div className="h-[40vh] relative">
        <div className="absolute inset-0 bg-[#003049]">
          <motion.div
            initial={{ y: -20, opacity: 0 }}
            animate={{ y: 0, opacity: 1 }}
            transition={{ duration: 0.5 }}
            className="flex justify-center items-center h-full"
          >
            <img
              src="/shark-logo.png"
              alt="Box Shark Logo"
              className="w-32 h-auto"
            />
          </motion.div>
        </div>
        <div className="absolute bottom-0 w-full">
          <svg
            viewBox="0 0 1440 320"
            className="w-full"
            preserveAspectRatio="none"
            height="100"
            fill="#ffffff"
          >
            <path d="M0,96L1440,32L1440,320L0,320Z"></path>
          </svg>
        </div>
      </div>

      <div className="bg-white min-h-[60vh] px-8 pt-6">
        <form onSubmit={handleSubmit} className="space-y-6">
          <motion.div
            initial={{ x: -50, opacity: 0 }}
            animate={{ x: 0, opacity: 1 }}
            transition={{ delay: 0.2 }}
          >
            <input
              type="text"
              value={login}
              onChange={(e) => setLogin(e.target.value)}
              className="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-[#003049] focus:outline-none"
              placeholder="Login"
            />
          </motion.div>

          <motion.div
            initial={{ x: -50, opacity: 0 }}
            animate={{ x: 0, opacity: 1 }}
            transition={{ delay: 0.3 }}
          >
            <input
              type="password"
              value={senha}
              onChange={(e) => setSenha(e.target.value)}
              className="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-[#003049] focus:outline-none"
              placeholder="Senha"
            />
          </motion.div>

          <motion.button
            initial={{ y: 20, opacity: 0 }}
            animate={{ y: 0, opacity: 1 }}
            transition={{ delay: 0.4 }}
            type="submit"
            className="w-full bg-[#003049] text-white rounded-lg py-3 font-medium"
          >
            ACESSAR
          </motion.button>
        </form>

        <motion.div
          initial={{ opacity: 0 }}
          animate={{ opacity: 1 }}
          transition={{ delay: 0.5 }}
          className="mt-6 space-y-4 text-center"
        >
          <a href="#" className="block text-[#003049] text-sm">
            Esqueceu sua senha?
          </a>
          <a href="#" className="block text-[#003049] text-sm">
            Primeiro acesso
          </a>
        </motion.div>
      </div>
    </div>
  );
};

export default LoginScreen; 