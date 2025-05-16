import { BrowserRouter as Router, Routes, Route, Link } from 'react-router-dom';
import ProductList from './components/ProductList.jsx';
import ReservationList from './components/ReservationList.jsx';
import ReservationForm from './components/ReservationForm.jsx';

export function App() {
  return (
    <Router>
      <div className="min-h-screen bg-gray-100">
        <nav className="bg-white shadow-md">
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div className="flex justify-between h-16">
              <div className="flex">
                <div className="flex-shrink-0 flex items-center">
                  <h1 className="text-xl font-bold text-indigo-600">Product Reservation System</h1>
                </div>
                <div className="ml-6 flex space-x-8">
                  <Link to="/" className="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                    Products
                  </Link>
                  <Link to="/reservations" className="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                    My Reservations
                  </Link>
                </div>
              </div>
            </div>
          </div>
        </nav>

        <div className="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
          <Routes>
            <Route path="/" element={<ProductList />} />
            <Route path="/reservations" element={<ReservationList />} />
            <Route path="/reserve/:id" element={<ReservationForm />} />
            <Route path="/edit-reservation/:id" element={<ReservationForm />} />
          </Routes>
        </div>
      </div>
    </Router>
  );
}

export default App; 