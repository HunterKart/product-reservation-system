// Use environment variable for API URL or fallback to localhost path
const API_BASE_URL = import.meta.env.VITE_API_URL || 'http://localhost/reservation-system-final/backend';

// API methods for products
export const getProducts = async () => {
  try {
    const response = await fetch(`${API_BASE_URL}/api/products`);
    if (!response.ok) {
      const errorData = await response.json().catch(() => ({}));
      throw new Error(errorData.message || `HTTP error: ${response.status}`);
    }
    return await response.json();
  } catch (error) {
    console.error('Error fetching products:', error);
    throw error;
  }
};

export const getProduct = async (id) => {
  try {
    const response = await fetch(`${API_BASE_URL}/api/products/${id}`);
    if (!response.ok) {
      const errorData = await response.json().catch(() => ({}));
      throw new Error(errorData.message || `HTTP error: ${response.status}`);
    }
    return await response.json();
  } catch (error) {
    console.error(`Error fetching product ${id}:`, error);
    throw error;
  }
};

// API methods for reservations
export const getReservations = async () => {
  try {
    const response = await fetch(`${API_BASE_URL}/api/reservations`);
    if (!response.ok) {
      const errorData = await response.json().catch(() => ({}));
      throw new Error(errorData.message || `HTTP error: ${response.status}`);
    }
    return await response.json();
  } catch (error) {
    console.error('Error fetching reservations:', error);
    throw error;
  }
};

export const getReservation = async (id) => {
  try {
    const response = await fetch(`${API_BASE_URL}/api/reservations/${id}`);
    if (!response.ok) {
      const errorData = await response.json().catch(() => ({}));
      throw new Error(errorData.message || `HTTP error: ${response.status}`);
    }
    return await response.json();
  } catch (error) {
    console.error(`Error fetching reservation ${id}:`, error);
    throw error;
  }
};

export const createReservation = async (reservationData) => {
  try {
    const response = await fetch(`${API_BASE_URL}/api/reservations`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(reservationData),
    });
    if (!response.ok) {
      const errorData = await response.json().catch(() => ({}));
      throw new Error(errorData.message || `HTTP error: ${response.status}`);
    }
    return await response.json();
  } catch (error) {
    console.error('Error creating reservation:', error);
    throw error;
  }
};

export const updateReservation = async (id, reservationData) => {
  try {
    const response = await fetch(`${API_BASE_URL}/api/reservations/${id}`, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(reservationData),
    });
    if (!response.ok) {
      const errorData = await response.json().catch(() => ({}));
      throw new Error(errorData.message || `HTTP error: ${response.status}`);
    }
    return await response.json();
  } catch (error) {
    console.error(`Error updating reservation ${id}:`, error);
    throw error;
  }
};

export const deleteReservation = async (id) => {
  try {
    const response = await fetch(`${API_BASE_URL}/api/reservations/${id}`, {
      method: 'DELETE',
    });
    if (!response.ok) {
      const errorData = await response.json().catch(() => ({}));
      throw new Error(errorData.message || `HTTP error: ${response.status}`);
    }
    return await response.json();
  } catch (error) {
    console.error(`Error deleting reservation ${id}:`, error);
    throw error;
  }
}; 