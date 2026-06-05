import { Stack } from 'expo-router';

/**
 * Auth stack – no headers, animation identical to old ConnexionStack + SignUpStack.
 * All screens in this group are unauthenticated (no token required).
 */
export default function AuthLayout() {
  return (
    <Stack screenOptions={{ headerShown: false, animation: 'slide_from_right' }}>
      <Stack.Screen name="ConnexionAcceuil" />
      <Stack.Screen name="SignIn" />
      <Stack.Screen name="PolitiqueConfidentialite" />
      <Stack.Screen name="SignUpUserTelephoneDetails" />
      <Stack.Screen name="SignUpUsernameScreen" />
      <Stack.Screen name="SignUpUserVerificationCodeScreen" />
      <Stack.Screen name="SignUpUserPersonalDetails" />
      <Stack.Screen name="SignUpUserPasswordDetails" />
    </Stack>
  );
}
