// PaymentTestScreen.js - Simplified untuk testing
import React, { useState } from 'react';
import {
  View,
  Text,
  StyleSheet,
  TouchableOpacity,
  ScrollView,
  TextInput,
  Alert,
  SafeAreaView,
  ActivityIndicator,
} from 'react-native';

const API_BASE_URL = 'http://10.0.2.2:8000/api'; // Android emulator
// const API_BASE_URL = 'http://192.168.1.100:8000/api'; // Ganti dengan IP komputer untuk device fisik

// Pre-filled dengan data login Anda
const AUTH_TOKEN = 'Bearer 45|9cAMnRfHMygufHWTAesnDOW78bPfDuBV5khYEhO8f1b54b14';
const USER_NAME = 'raka';
const USER_EMAIL = 'raka@gmail.com';

const PaymentTestScreen = () => {
  const [loading, setLoading] = useState(false);
  const [result, setResult] = useState('');

  const testConnection = async () => {
    setLoading(true);
    try {
      const response = await fetch(`${API_BASE_URL}/payment/public-key`);
      const data = await response.json();
      
      if (data.success) {
        Alert.alert('Success', 'Connection successful!');
        setResult(JSON.stringify(data, null, 2));
      } else {
        Alert.alert('Error', 'Connection failed');
      }
    } catch (error) {
      Alert.alert('Error', error.message);
    } finally {
      setLoading(false);
    }
  };

  const testPaymentMethods = async () => {
    setLoading(true);
    try {
      const response = await fetch(`${API_BASE_URL}/payment/methods`);
      const data = await response.json();
      
      if (data.success) {
        Alert.alert('Success', `Found ${data.data.length} payment methods`);
        setResult(JSON.stringify(data, null, 2));
      } else {
        Alert.alert('Error', 'Failed to get payment methods');
      }
    } catch (error) {
      Alert.alert('Error', error.message);
    } finally {
      setLoading(false);
    }
  };

  const testBankTransfer = async () => {
    setLoading(true);
    try {
      const response = await fetch(`${API_BASE_URL}/test/orders/1/payment`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': AUTH_TOKEN
        },
        body: JSON.stringify({
          payment_method: 'bank_transfer',
          payment_channel: 'BCA',
          customer_phone: '08123456789'
        })
      });

      const data = await response.json();
      
      if (data.success) {
        Alert.alert('Success', 'Bank Transfer payment created!');
        setResult(JSON.stringify(data, null, 2));
      } else {
        Alert.alert('Error', data.message || 'Payment failed');
      }
    } catch (error) {
      Alert.alert('Error', error.message);
    } finally {
      setLoading(false);
    }
  };

  const testEWallet = async () => {
    setLoading(true);
    try {
      const response = await fetch(`${API_BASE_URL}/test/orders/2/payment`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': AUTH_TOKEN
        },
        body: JSON.stringify({
          payment_method: 'e_wallet',
          payment_channel: 'OVO',
          customer_phone: '08987654321'
        })
      });

      const data = await response.json();
      
      if (data.success) {
        Alert.alert('Success', 'E-Wallet payment created!');
        setResult(JSON.stringify(data, null, 2));
      } else {
        Alert.alert('Error', data.message || 'Payment failed');
      }
    } catch (error) {
      Alert.alert('Error', error.message);
    } finally {
      setLoading(false);
    }
  };

  const testQRIS = async () => {
    setLoading(true);
    try {
      const response = await fetch(`${API_BASE_URL}/test/orders/3/payment`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': AUTH_TOKEN
        },
        body: JSON.stringify({
          payment_method: 'qris'
        })
      });

      const data = await response.json();
      
      if (data.success) {
        Alert.alert('Success', 'QRIS payment created!');
        setResult(JSON.stringify(data, null, 2));
      } else {
        Alert.alert('Error', data.message || 'Payment failed');
      }
    } catch (error) {
      Alert.alert('Error', error.message);
    } finally {
      setLoading(false);
    }
  };

  return (
    <SafeAreaView style={styles.container}>
      <ScrollView style={styles.scrollView}>
        {/* Header */}
        <View style={styles.header}>
          <Text style={styles.title}>Xendit Payment Test</Text>
          <Text style={styles.subtitle}>User: {USER_NAME} ({USER_EMAIL})</Text>
        </View>

        {/* Test Buttons */}
        <View style={styles.buttonContainer}>
          <TouchableOpacity style={styles.button} onPress={testConnection} disabled={loading}>
            <Text style={styles.buttonText}>1. Test Connection</Text>
          </TouchableOpacity>

          <TouchableOpacity style={styles.button} onPress={testPaymentMethods} disabled={loading}>
            <Text style={styles.buttonText}>2. Get Payment Methods</Text>
          </TouchableOpacity>

          <TouchableOpacity style={[styles.button, styles.paymentButton]} onPress={testBankTransfer} disabled={loading}>
            <Text style={styles.buttonText}>3. Test Bank Transfer (BCA)</Text>
          </TouchableOpacity>

          <TouchableOpacity style={[styles.button, styles.paymentButton]} onPress={testEWallet} disabled={loading}>
            <Text style={styles.buttonText}>4. Test E-Wallet (OVO)</Text>
          </TouchableOpacity>

          <TouchableOpacity style={[styles.button, styles.paymentButton]} onPress={testQRIS} disabled={loading}>
            <Text style={styles.buttonText}>5. Test QRIS</Text>
          </TouchableOpacity>
        </View>

        {/* Loading Indicator */}
        {loading && (
          <View style={styles.loadingContainer}>
            <ActivityIndicator size="large" color="#007bff" />
            <Text style={styles.loadingText}>Testing...</Text>
          </View>
        )}

        {/* Result Display */}
        {result !== '' && (
          <View style={styles.resultContainer}>
            <Text style={styles.resultTitle}>API Response:</Text>
            <ScrollView style={styles.resultScroll}>
              <Text style={styles.resultText}>{result}</Text>
            </ScrollView>
          </View>
        )}
      </ScrollView>
    </SafeAreaView>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#f8f9fa',
  },
  scrollView: {
    flex: 1,
    padding: 16,
  },
  header: {
    alignItems: 'center',
    marginBottom: 24,
    padding: 16,
    backgroundColor: '#fff',
    borderRadius: 8,
    elevation: 2,
  },
  title: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#007bff',
  },
  subtitle: {
    fontSize: 14,
    color: '#6c757d',
    marginTop: 4,
  },
  buttonContainer: {
    marginBottom: 24,
  },
  button: {
    backgroundColor: '#007bff',
    padding: 16,
    borderRadius: 8,
    marginVertical: 8,
    alignItems: 'center',
  },
  paymentButton: {
    backgroundColor: '#28a745',
  },
  buttonText: {
    color: '#fff',
    fontSize: 16,
    fontWeight: 'bold',
  },
  loadingContainer: {
    alignItems: 'center',
    padding: 20,
  },
  loadingText: {
    marginTop: 8,
    color: '#6c757d',
  },
  resultContainer: {
    backgroundColor: '#fff',
    borderRadius: 8,
    padding: 16,
    elevation: 2,
  },
  resultTitle: {
    fontSize: 18,
    fontWeight: 'bold',
    marginBottom: 12,
    color: '#212529',
  },
  resultScroll: {
    maxHeight: 300,
    backgroundColor: '#f8f9fa',
    borderRadius: 4,
    padding: 12,
  },
  resultText: {
    fontSize: 12,
    fontFamily: 'monospace',
    color: '#212529',
  },
});

export default PaymentTestScreen;
