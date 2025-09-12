// PaymentScreen.js - React Native Demo for Xendit Payment
import React, { useState, useEffect } from 'react';
import {
  View,
  Text,
  StyleSheet,
  TouchableOpacity,
  ScrollView,
  TextInput,
  Alert,
  Linking,
  Modal,
  SafeAreaView,
  ActivityIndicator,
} from 'react-native';
import Icon from 'react-native-vector-icons/MaterialIcons';

const API_BASE_URL = 'http://10.0.2.2:8000/api'; // For Android emulator
// const API_BASE_URL = 'http://localhost:8000/api'; // For iOS simulator

const PaymentScreen = () => {
  const [paymentMethods, setPaymentMethods] = useState([]);
  const [selectedMethod, setSelectedMethod] = useState('');
  const [selectedChannel, setSelectedChannel] = useState('');
  const [customerPhone, setCustomerPhone] = useState('');
  const [authToken, setAuthToken] = useState('');
  const [loading, setLoading] = useState(false);
  const [paymentModalVisible, setPaymentModalVisible] = useState(false);
  const [paymentData, setPaymentData] = useState(null);

  // Mock order data
  const orderData = {
    id: '001',
    orderNumber: 'ORD-2025-001',
    total: 450000,
    items: 3,
    status: 'pending_payment'
  };

  useEffect(() => {
    loadPaymentMethods();
  }, []);

  const loadPaymentMethods = async () => {
    try {
      const response = await fetch(`${API_BASE_URL}/payment/methods`);
      const data = await response.json();
      
      if (data.success) {
        setPaymentMethods(data.data);
      }
    } catch (error) {
      Alert.alert('Error', 'Failed to load payment methods');
    }
  };

  const selectPaymentMethod = (method) => {
    setSelectedMethod(method.id);
    setSelectedChannel(''); // Reset channel selection
  };

  const selectChannel = (channelCode) => {
    setSelectedChannel(channelCode);
  };

  const createPayment = async () => {
    if (!selectedMethod) {
      Alert.alert('Error', 'Please select a payment method');
      return;
    }

    if (!authToken) {
      Alert.alert('Error', 'Please enter auth token');
      return;
    }

    setLoading(true);

    try {
      const paymentRequestData = {
        payment_method: selectedMethod,
        payment_channel: selectedChannel || null,
        customer_phone: customerPhone || null
      };

      const response = await fetch(`${API_BASE_URL}/orders/${orderData.id}/payment`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': authToken
        },
        body: JSON.stringify(paymentRequestData)
      });

      const data = await response.json();

      if (data.success) {
        setPaymentData(data.data);
        setPaymentModalVisible(true);
      } else {
        Alert.alert('Error', data.message || 'Failed to create payment');
      }

    } catch (error) {
      Alert.alert('Error', 'Network error: ' + error.message);
    } finally {
      setLoading(false);
    }
  };

  const openInvoice = () => {
    if (paymentData?.invoice_url) {
      Linking.openURL(paymentData.invoice_url);
    }
  };

  const renderPaymentMethod = (method) => {
    const isSelected = selectedMethod === method.id;
    
    return (
      <TouchableOpacity
        key={method.id}
        style={[styles.paymentCard, isSelected && styles.selectedCard]}
        onPress={() => selectPaymentMethod(method)}
      >
        <View style={styles.paymentCardContent}>
          <Icon name={getMethodIcon(method.icon)} size={32} color="#007bff" />
          <View style={styles.paymentCardText}>
            <Text style={styles.paymentMethodName}>{method.name}</Text>
            <Text style={styles.paymentMethodDesc}>{method.description}</Text>
          </View>
        </View>
      </TouchableOpacity>
    );
  };

  const renderChannels = () => {
    const selectedMethodData = paymentMethods.find(m => m.id === selectedMethod);
    
    if (!selectedMethodData?.channels || selectedMethodData.channels.length === 0) {
      return null;
    }

    return (
      <View style={styles.channelsContainer}>
        <Text style={styles.sectionTitle}>Select Payment Channel:</Text>
        <View style={styles.channelsGrid}>
          {selectedMethodData.channels.map((channel) => (
            <TouchableOpacity
              key={channel.code}
              style={[
                styles.channelOption,
                selectedChannel === channel.code && styles.selectedChannel
              ]}
              onPress={() => selectChannel(channel.code)}
            >
              <Text style={[
                styles.channelText,
                selectedChannel === channel.code && styles.selectedChannelText
              ]}>
                {channel.name}
              </Text>
            </TouchableOpacity>
          ))}
        </View>
      </View>
    );
  };

  const getMethodIcon = (icon) => {
    const iconMap = {
      'bank': 'account-balance',
      'wallet': 'account-balance-wallet',
      'store': 'store',
      'qr': 'qr-code',
      'credit-card': 'credit-card'
    };
    return iconMap[icon] || 'payment';
  };

  return (
    <SafeAreaView style={styles.container}>
      <ScrollView style={styles.scrollView}>
        {/* Header */}
        <View style={styles.header}>
          <Text style={styles.headerTitle}>Xendit Payment Demo</Text>
          <Text style={styles.headerSubtitle}>Iwak Mart</Text>
        </View>

        {/* Order Summary */}
        <View style={styles.orderSummary}>
          <Text style={styles.sectionTitle}>Order Summary</Text>
          <View style={styles.orderRow}>
            <Text>Order ID: {orderData.orderNumber}</Text>
            <Text>Total Items: {orderData.items}</Text>
          </View>
          <View style={styles.orderRow}>
            <Text style={styles.totalAmount}>Total: Rp {orderData.total.toLocaleString()}</Text>
            <Text style={styles.statusBadge}>Pending Payment</Text>
          </View>
        </View>

        {/* Auth Token Input */}
        <View style={styles.authSection}>
          <Text style={styles.sectionTitle}>Authentication</Text>
          <TextInput
            style={styles.textInput}
            placeholder="Enter Bearer token from login"
            value={authToken}
            onChangeText={setAuthToken}
            multiline
            numberOfLines={3}
          />
        </View>

        {/* Payment Methods */}
        <View style={styles.paymentSection}>
          <Text style={styles.sectionTitle}>Select Payment Method</Text>
          {paymentMethods.map(renderPaymentMethod)}
        </View>

        {/* Payment Channels */}
        {renderChannels()}

        {/* Customer Phone (for e-wallet) */}
        {selectedMethod === 'e_wallet' && (
          <View style={styles.phoneSection}>
            <Text style={styles.sectionTitle}>Phone Number (for e-wallet)</Text>
            <TextInput
              style={styles.textInput}
              placeholder="08123456789"
              value={customerPhone}
              onChangeText={setCustomerPhone}
              keyboardType="phone-pad"
            />
          </View>
        )}

        {/* Pay Button */}
        <TouchableOpacity
          style={[styles.payButton, !selectedMethod && styles.payButtonDisabled]}
          onPress={createPayment}
          disabled={!selectedMethod || loading}
        >
          {loading ? (
            <ActivityIndicator color="#fff" />
          ) : (
            <>
              <Icon name="payment" size={24} color="#fff" />
              <Text style={styles.payButtonText}>Pay Now</Text>
            </>
          )}
        </TouchableOpacity>
      </ScrollView>

      {/* Payment Success Modal */}
      <Modal
        animationType="slide"
        transparent={true}
        visible={paymentModalVisible}
        onRequestClose={() => setPaymentModalVisible(false)}
      >
        <View style={styles.modalContainer}>
          <View style={styles.modalContent}>
            <View style={styles.modalHeader}>
              <Icon name="check-circle" size={48} color="#28a745" />
              <Text style={styles.modalTitle}>Payment Created!</Text>
            </View>

            {paymentData && (
              <View style={styles.paymentDetails}>
                <Text style={styles.detailLabel}>Payment ID:</Text>
                <Text style={styles.detailValue}>{paymentData.payment_id}</Text>
                
                <Text style={styles.detailLabel}>Amount:</Text>
                <Text style={styles.detailValue}>Rp {paymentData.amount?.toLocaleString()}</Text>
                
                <Text style={styles.detailLabel}>Status:</Text>
                <Text style={[styles.detailValue, styles.statusPending]}>{paymentData.status}</Text>
                
                <Text style={styles.detailLabel}>Expires:</Text>
                <Text style={styles.detailValue}>
                  {paymentData.expired_at ? new Date(paymentData.expired_at).toLocaleString() : 'N/A'}
                </Text>
              </View>
            )}

            <View style={styles.modalActions}>
              <TouchableOpacity
                style={styles.modalButton}
                onPress={() => setPaymentModalVisible(false)}
              >
                <Text style={styles.modalButtonText}>Close</Text>
              </TouchableOpacity>
              
              <TouchableOpacity
                style={[styles.modalButton, styles.primaryButton]}
                onPress={openInvoice}
              >
                <Icon name="open-in-new" size={20} color="#fff" />
                <Text style={[styles.modalButtonText, styles.primaryButtonText]}>Open Invoice</Text>
              </TouchableOpacity>
            </View>
          </View>
        </View>
      </Modal>
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
  },
  headerTitle: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#007bff',
  },
  headerSubtitle: {
    fontSize: 16,
    color: '#6c757d',
  },
  orderSummary: {
    backgroundColor: '#fff',
    padding: 16,
    borderRadius: 8,
    marginBottom: 16,
    elevation: 2,
  },
  orderRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    marginVertical: 4,
  },
  totalAmount: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#28a745',
  },
  statusBadge: {
    backgroundColor: '#ffc107',
    color: '#212529',
    paddingHorizontal: 8,
    paddingVertical: 4,
    borderRadius: 4,
    fontSize: 12,
  },
  authSection: {
    backgroundColor: '#fff',
    padding: 16,
    borderRadius: 8,
    marginBottom: 16,
    elevation: 2,
  },
  paymentSection: {
    backgroundColor: '#fff',
    padding: 16,
    borderRadius: 8,
    marginBottom: 16,
    elevation: 2,
  },
  sectionTitle: {
    fontSize: 18,
    fontWeight: 'bold',
    marginBottom: 12,
    color: '#212529',
  },
  paymentCard: {
    borderWidth: 2,
    borderColor: '#e9ecef',
    borderRadius: 8,
    padding: 16,
    marginVertical: 8,
  },
  selectedCard: {
    borderColor: '#007bff',
    backgroundColor: '#f8f9ff',
  },
  paymentCardContent: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  paymentCardText: {
    marginLeft: 16,
    flex: 1,
  },
  paymentMethodName: {
    fontSize: 16,
    fontWeight: 'bold',
    color: '#212529',
  },
  paymentMethodDesc: {
    fontSize: 14,
    color: '#6c757d',
  },
  channelsContainer: {
    backgroundColor: '#fff',
    padding: 16,
    borderRadius: 8,
    marginBottom: 16,
    elevation: 2,
  },
  channelsGrid: {
    flexDirection: 'row',
    flexWrap: 'wrap',
    gap: 8,
  },
  channelOption: {
    borderWidth: 1,
    borderColor: '#dee2e6',
    borderRadius: 6,
    padding: 12,
    minWidth: 100,
    alignItems: 'center',
  },
  selectedChannel: {
    backgroundColor: '#007bff',
    borderColor: '#007bff',
  },
  channelText: {
    fontSize: 12,
    color: '#212529',
    textAlign: 'center',
  },
  selectedChannelText: {
    color: '#fff',
  },
  phoneSection: {
    backgroundColor: '#fff',
    padding: 16,
    borderRadius: 8,
    marginBottom: 16,
    elevation: 2,
  },
  textInput: {
    borderWidth: 1,
    borderColor: '#ced4da',
    borderRadius: 6,
    padding: 12,
    fontSize: 16,
    backgroundColor: '#fff',
  },
  payButton: {
    backgroundColor: '#007bff',
    padding: 16,
    borderRadius: 8,
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'center',
    marginVertical: 16,
  },
  payButtonDisabled: {
    backgroundColor: '#6c757d',
  },
  payButtonText: {
    color: '#fff',
    fontSize: 18,
    fontWeight: 'bold',
    marginLeft: 8,
  },
  modalContainer: {
    flex: 1,
    backgroundColor: 'rgba(0, 0, 0, 0.5)',
    justifyContent: 'center',
    alignItems: 'center',
    padding: 16,
  },
  modalContent: {
    backgroundColor: '#fff',
    borderRadius: 12,
    padding: 24,
    width: '100%',
    maxWidth: 400,
  },
  modalHeader: {
    alignItems: 'center',
    marginBottom: 24,
  },
  modalTitle: {
    fontSize: 20,
    fontWeight: 'bold',
    marginTop: 8,
    color: '#212529',
  },
  paymentDetails: {
    marginBottom: 24,
  },
  detailLabel: {
    fontSize: 14,
    color: '#6c757d',
    marginBottom: 4,
  },
  detailValue: {
    fontSize: 16,
    fontWeight: 'bold',
    color: '#212529',
    marginBottom: 12,
  },
  statusPending: {
    color: '#ffc107',
  },
  modalActions: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    gap: 12,
  },
  modalButton: {
    flex: 1,
    padding: 12,
    borderRadius: 6,
    borderWidth: 1,
    borderColor: '#6c757d',
    alignItems: 'center',
  },
  primaryButton: {
    backgroundColor: '#007bff',
    borderColor: '#007bff',
    flexDirection: 'row',
  },
  modalButtonText: {
    fontSize: 16,
    color: '#6c757d',
  },
  primaryButtonText: {
    color: '#fff',
    marginLeft: 4,
  },
});

export default PaymentScreen;
