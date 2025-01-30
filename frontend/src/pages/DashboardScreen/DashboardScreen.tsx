import { motion } from 'framer-motion';

const DashboardScreen = () => {
  const menuItems = [
    { title: 'Modalidades', icon: '🏋️‍♂️' },
    { title: 'Frequências', icon: '📊' },
    { title: 'Eventos', icon: '📅' },
    { title: 'Promoções', icon: '🎯' },
    { title: 'Inscrições', icon: '📝' },
    { title: 'Pagamentos', icon: '💳' },
  ];

  return (
    <div className="min-h-screen bg-gray-100">
      {/* Header */}
      <header className="bg-[#003049] text-white p-4">
        <div className="flex justify-between items-center">
          <motion.div
            initial={{ x: -20, opacity: 0 }}
            animate={{ x: 0, opacity: 1 }}
          >
            <img
              src="/shark-logo.png"
              alt="Box Shark Logo"
              className="h-8"
            />
          </motion.div>
          <motion.button
            initial={{ x: 20, opacity: 0 }}
            animate={{ x: 0, opacity: 1 }}
            className="text-2xl"
          >
            ☰
          </motion.button>
        </div>
        
        <motion.div
          initial={{ y: -20, opacity: 0 }}
          animate={{ y: 0, opacity: 1 }}
          transition={{ delay: 0.2 }}
          className="mt-4"
        >
          <h1 className="text-lg">Olá, Danilson Shark</h1>
          <p className="text-sm opacity-80">17 de Dezembro de 2024</p>
        </motion.div>
      </header>

      {/* Menu Grid */}
      <div className="p-4 grid grid-cols-2 gap-4">
        {menuItems.map((item, index) => (
          <motion.div
            key={item.title}
            initial={{ scale: 0, opacity: 0 }}
            animate={{ scale: 1, opacity: 1 }}
            transition={{ delay: index * 0.1 }}
            className="bg-white rounded-lg p-6 shadow-md flex flex-col items-center justify-center space-y-2"
          >
            <span className="text-2xl">{item.icon}</span>
            <span className="text-sm text-gray-700">{item.title}</span>
          </motion.div>
        ))}
      </div>
    </div>
  );
};

export default DashboardScreen; 