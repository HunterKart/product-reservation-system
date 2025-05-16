import { useState, useEffect } from 'react';
import { useParams, useNavigate, useLocation } from 'react-router-dom';
import { getProduct, getReservation, createReservation, updateReservation } from '../services/api.js';

export function ReservationForm() {
  const { id } = useParams();
  const navigate = useNavigate();
  const location = useLocation();
  const isEditing = location.pathname.includes('edit-reservation');

  const [formData, setFormData] = useState({
    product_id: id,
    name: 'Jimarnie Branzuela', // Updated default name for the single user
    quantity: 1,
    status: 'pending',
    reservation_date: formatDate(new Date()) // Default to today's date, formatted as YYYY-MM-DD
  });
  
  const [product, setProduct] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [submitting, setSubmitting] = useState(false);
  const [formErrors, setFormErrors] = useState({});

  // Helper function to format date as YYYY-MM-DD
  function formatDate(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0'); // Add leading zero if needed
    const day = String(date.getDate()).padStart(2, '0'); // Add leading zero if needed
    return `${year}-${month}-${day}`;
  }

  // Get the maximum date (1 month from today)
  function getMaxDate() {
    const maxDate = new Date();
    maxDate.setMonth(maxDate.getMonth() + 1); // Add 1 month
    return formatDate(maxDate);
  }

  useEffect(() => {
    const fetchData = async () => {
      try {
        setLoading(true);
        
        if (isEditing) {
          // If editing, fetch the reservation details
          const reservation = await getReservation(id);
          setFormData({
            product_id: reservation.product_id,
            name: 'Jimarnie Branzuela', // Always use this specific name
            quantity: reservation.quantity,
            status: reservation.status,
            reservation_date: reservation.date ? reservation.date.split(' ')[0] : formatDate(new Date()) // Extract date part if available
          });
          // Also fetch the product details for display
          const productData = await getProduct(reservation.product_id);
          setProduct(productData);
        } else {
          // If creating new, fetch the product details
          const productData = await getProduct(id);
          setProduct(productData);
        }
        
        setError(null);
      } catch (err) {
        setError(`Failed to load ${isEditing ? 'reservation' : 'product'} details. Please try again later.`);
        console.error(`Error fetching data:`, err);
      } finally {
        setLoading(false);
      }
    };

    fetchData();
  }, [id, isEditing]);

  const validateForm = () => {
    const errors = {};
    
    if (!formData.quantity || formData.quantity <= 0) {
      errors.quantity = 'Quantity must be greater than 0';
    } else if (formData.quantity > (product?.quantity || 0) && !isEditing) {
      errors.quantity = `Quantity cannot exceed available stock (${product.quantity})`;
    }
    
    if (!formData.reservation_date) {
      errors.reservation_date = 'Please select a reservation date';
    } else {
      // Check if the selected date is not in the past
      const selectedDate = new Date(formData.reservation_date);
      const today = new Date();
      today.setHours(0, 0, 0, 0); // Reset time to start of day for comparison
      
      // Calculate maximum allowed date (1 month from today)
      const maxDate = new Date();
      maxDate.setMonth(maxDate.getMonth() + 1);
      maxDate.setHours(23, 59, 59, 999); // Set to end of day
      
      if (selectedDate < today) {
        errors.reservation_date = 'Reservation date cannot be in the past';
      } else if (selectedDate > maxDate) {
        errors.reservation_date = 'Reservation date cannot be more than 1 month from today';
      }
    }
    
    setFormErrors(errors);
    return Object.keys(errors).length === 0;
  };

  const handleChange = (e) => {
    const { name, value } = e.target;
    
    if (name === 'quantity') {
      const numValue = parseInt(value, 10);
      if (isNaN(numValue) || numValue < 1) return;
    }
    
    setFormData(prev => ({
      ...prev,
      [name]: name === 'quantity' ? parseInt(value, 10) : value
    }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    
    if (!validateForm()) return;
    
    try {
      setSubmitting(true);
      
      // Create a copy of the form data with the correct field names for the API
      const apiData = {
        ...formData,
        // Use the reservation_date as date for the API
        reservation_date: formData.reservation_date
      };
      
      if (isEditing) {
        await updateReservation(id, apiData);
      } else {
        await createReservation(apiData);
      }
      
      // Redirect to reservations list
      navigate('/reservations');
    } catch (err) {
      setError(`Failed to ${isEditing ? 'update' : 'create'} reservation. ${err.message || 'Please try again later.'}`);
      console.error(`Error ${isEditing ? 'updating' : 'creating'} reservation:`, err);
      setSubmitting(false);
    }
  };

  if (loading) {
    return (
      <div className="text-center py-10">
        <div className="inline-block animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-indigo-600"></div>
        <p className="mt-2 text-gray-600">Loading...</p>
      </div>
    );
  }

  if (error) {
    return (
      <div className="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <strong className="font-bold">Error: </strong>
        <span className="block sm:inline">{error}</span>
        <button
          onClick={() => navigate(-1)}
          className="mt-4 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
        >
          Go Back
        </button>
      </div>
    );
  }

  return (
    <div className="max-w-md mx-auto bg-white rounded-xl shadow-md overflow-hidden md:max-w-2xl">
      <div className="md:flex">
        <div className="p-8 w-full">
          <div className="uppercase tracking-wide text-sm text-indigo-500 font-semibold">
            {isEditing ? 'Edit Reservation' : 'New Reservation'}
          </div>
          
          {product && (
            <div className="mt-2">
              <h2 className="text-xl font-bold">{product.name}</h2>
              <p className="mt-1 text-gray-500">{product.description}</p>
              {!isEditing && (
                <div className="mt-2">
                  <span className={`px-2 py-1 text-xs font-semibold rounded-full ${
                    product.quantity > 10 
                      ? 'bg-green-100 text-green-800' 
                      : product.quantity > 0 
                        ? 'bg-yellow-100 text-yellow-800' 
                        : 'bg-red-100 text-red-800'
                  }`}>
                    {product.quantity} in stock
                  </span>
                </div>
              )}
            </div>
          )}
          
          <form onSubmit={handleSubmit} className="mt-6">
            {/* Display user name (non-editable) */}
            <div className="mb-4">
              <label className="block text-gray-700 text-sm font-bold mb-2">
                Reserved By
              </label>
              <div className="shadow appearance-none border border-gray-300 rounded w-full py-2 px-3 text-gray-700 bg-gray-100">
                {formData.name}
              </div>
            </div>
            
            <div className="mb-4">
              <label htmlFor="quantity" className="block text-gray-700 text-sm font-bold mb-2">
                Quantity*
              </label>
              <input
                type="number"
                id="quantity"
                name="quantity"
                value={formData.quantity}
                onChange={handleChange}
                min="1"
                max={isEditing ? undefined : product?.quantity}
                className={`shadow appearance-none border ${formErrors.quantity ? 'border-red-500' : 'border-gray-300'} rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline`}
              />
              {formErrors.quantity && (
                <p className="text-red-500 text-xs italic">{formErrors.quantity}</p>
              )}
            </div>
            
            {/* Add date picker */}
            <div className="mb-4">
              <label htmlFor="reservation_date" className="block text-gray-700 text-sm font-bold mb-2">
                Reservation Date*
              </label>
              <input
                type="date"
                id="reservation_date"
                name="reservation_date"
                value={formData.reservation_date}
                onChange={handleChange}
                min={formatDate(new Date())} // Prevent selecting dates in the past
                max={getMaxDate()} // Limit to 1 month from today
                className={`shadow appearance-none border ${formErrors.reservation_date ? 'border-red-500' : 'border-gray-300'} rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline`}
              />
              {formErrors.reservation_date && (
                <p className="text-red-500 text-xs italic">{formErrors.reservation_date}</p>
              )}
            </div>
            
            {isEditing && (
              <div className="mb-6">
                <label htmlFor="status" className="block text-gray-700 text-sm font-bold mb-2">
                  Status
                </label>
                <select
                  id="status"
                  name="status"
                  value={formData.status}
                  onChange={handleChange}
                  className="shadow appearance-none border border-gray-300 rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                >
                  <option value="pending">Pending</option>
                  <option value="confirmed">Confirmed</option>
                  <option value="canceled">Canceled</option>
                  <option value="delivered">Delivered</option>
                  <option value="returned">Returned</option>
                </select>
              </div>
            )}
            
            <div className="flex items-center justify-between">
              <button
                type="submit"
                disabled={submitting}
                className={`bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline ${submitting ? 'opacity-50 cursor-not-allowed' : ''}`}
              >
                {submitting ? 'Processing...' : isEditing ? 'Update Reservation' : 'Complete Reservation'}
              </button>
              
              <button
                type="button"
                onClick={() => navigate(-1)}
                className="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
              >
                Cancel
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  );
}

export default ReservationForm; 