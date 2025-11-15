// Netlify serverless function to fetch weather data
exports.handler = async (event, context) => {
  const fetch = require('node-fetch');
  
  // Settings
  const city = 'Isulan, Sultan Kudarat, Philippines';
  const lat = 6.6297;
  const lon = 124.7472;
  
  try {
    // Fetch weather data from Open-Meteo API
    const apiUrl = `https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&hourly=temperature_2m,precipitation,weathercode&timezone=auto`;
    const response = await fetch(apiUrl);
    
    if (!response.ok) {
      throw new Error('Failed to fetch weather data');
    }
    
    const data = await response.json();
    
    // Process forecast data
    const forecast = [];
    if (data.hourly && data.hourly.time) {
      const now = new Date();
      const today = now.toISOString().split('T')[0];
      const tomorrow = new Date(now.getTime() + 86400000).toISOString().split('T')[0];
      
      const weatherCodes = {
        0: 'Clear',
        1: 'Mostly clear',
        2: 'Partly cloudy',
        3: 'Cloudy',
        45: 'Foggy',
        48: 'Foggy',
        51: 'Light rain',
        53: 'Light rain',
        55: 'Light rain',
        56: 'Freezing rain',
        57: 'Freezing rain',
        61: 'Light rain',
        63: 'Rain',
        65: 'Heavy rain',
        66: 'Freezing rain',
        67: 'Heavy freezing rain',
        71: 'Light snow',
        73: 'Snow',
        75: 'Heavy snow',
        77: 'Snow',
        80: 'Light rain',
        81: 'Rain showers',
        82: 'Heavy rain',
        85: 'Light snow',
        86: 'Heavy snow',
        95: 'Thunderstorm',
        96: 'Thunderstorm',
        99: 'Thunderstorm'
      };
      
      data.hourly.time.forEach((isoTime, i) => {
        const dt = new Date(isoTime);
        const date = dt.toISOString().split('T')[0];
        
        if (date === today || date === tomorrow) {
          const monthDayYear = dt.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
          const hour = dt.toLocaleTimeString('en-US', { hour: 'numeric', hour12: true });
          const temp = data.hourly.temperature_2m[i] ? Math.round(data.hourly.temperature_2m[i]) : null;
          const precip = data.hourly.precipitation[i] || 0;
          const code = data.hourly.weathercode[i];
          const condition = weatherCodes[code] || 'Unknown';
          
          let desc = condition;
          if (precip > 0) {
            desc += ' with rain';
          } else if (temp !== null && temp >= 32) {
            desc += ' and hot';
          }
          
          forecast.push({
            date: monthDayYear,
            hour: hour,
            temperature: temp,
            precipitation: precip,
            condition: desc
          });
        }
      });
    }
    
    // Return result
    const result = {
      location: city,
      latitude: lat,
      longitude: lon,
      forecast: forecast,
      timestamp: Math.floor(Date.now() / 1000),
      source: 'live',
      last_update: new Date().toISOString().replace('T', ' ').substring(0, 19)
    };
    
    return {
      statusCode: 200,
      headers: {
        'Content-Type': 'application/json; charset=utf-8',
        'Access-Control-Allow-Origin': '*'
      },
      body: JSON.stringify(result)
    };
    
  } catch (error) {
    return {
      statusCode: 500,
      headers: {
        'Content-Type': 'application/json; charset=utf-8'
      },
      body: JSON.stringify({ error: 'Failed to fetch weather data: ' + error.message })
    };
  }
};
